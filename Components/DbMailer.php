<?php

namespace Modules\Mail\Components;

use Mindy\Exception\HttpException;
use Mindy\Base\Mindy;
use Mindy\Helper\Console;
use Mindy\Mail\Mailer;
use Modules\Core\Components\ParamsHelper;
use Modules\Mail\Models\Mail;
use Modules\Mail\Models\MailTemplate;

class DbMailer extends Mailer
{
    public $debug = false;

    public $checkerDomain = '';

    /**
     * @var bool enable reading email checker
     */
    public $checker = true;

    public function fromCode($code, $receiver, $data = [], $attachments = [], $template = 'mail/message')
    {
        $uniq = uniqid();
        $template = $this->loadTemplateModel($code);
        
        $site = null;
        if (Mindy::app()->hasModule('Sites')) {
            $site = Mindy::app()->getModule('Sites')->getSite();
        }

        $subject = $this->renderString($template->subject, array_merge(['site' => $site], $data));
        $message = $this->renderString($template->template, array_merge(['site' => $site], $data));

        $checker = '';
        if ($this->checker) {
            $url = $this->checkerDomain . Mindy::app()->urlManager->reverse('mail:checker', ['uniqueId' => $uniq]);
            $checker = strtr("<img style='width: 1px !important; height: 1px !important;' src='{url}'>", [
                '{url}' => $url
            ]);
        }

        $text = $this->renderTemplate($template . ".txt", [
            'content' => $message,
            'subject' => $subject
        ]);
        $html = $this->renderTemplate($template . ".html", [
            'content' => $message . $checker,
            'subject' => $subject
        ]);

        /** @var \Mindy\Mail\MessageInterface $msg */
        $msg = $this->createMessage();
        $msg->setHtmlBody($html);
        if (isset($text)) {
            $msg->setTextBody($text);
        } else if (isset($html)) {
            if (preg_match('|<body[^>]*>(.*?)</body>|is', $html, $match)) {
                $html = $match[1];
            }
            $html = preg_replace('|<style[^>]*>(.*?)</style>|is', '', $html);
            $msg->setTextBody(strip_tags($html));
        }

        $msg->setTo($receiver);
        $msg->setFrom(Mindy::app()->managers);
        $msg->setSubject($subject);
        if (!empty($attachments)) {
            if (!is_array($attachments)) {
                $attachments = [$attachments];
            }
            foreach ($attachments as $file) {
                $msg->attach($file);
            }
        }

        $receivers = [];
        if (is_array($receiver)) {
            foreach ($receiver as $r) {
                $receivers[] = $r;
            }
        } else {
            $receivers[] = $receiver;
        }

        $module = Mindy::app()->getModule('Mail');
        $result = $module->delayedSend ? true : $msg->send();
        if ($result) {
            $model = new Mail([
                'email' => implode(', ', $receivers),
                'subject' => $subject,
                'message_txt' => $text,
                'message_html' => $html,
                'unique_id' => $uniq,
                'is_sended' => true
            ]);
            $model->save();
            return [$subject, $message];
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getDomain()
    {
        if ($this->domain) {
            return $this->domain;
        } else if (Console::isCli() === false) {
            return Mindy::app()->request->http->getHostInfo();
        }

        return null;
    }

    /**
     * @param $code
     * @return bool|\Mindy\Orm\Orm|null
     * @throws HttpException
     */
    protected function loadTemplateModel($code)
    {
        $maildb = MailTemplate::objects()->filter(['code' => $code])->get();
        if ($maildb === null) {
            throw new HttpException(500, "Mail template with code $code do not exists");
        }
        return $maildb;
    }
}
