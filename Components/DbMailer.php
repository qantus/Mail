<?php

namespace Modules\Mail\Components;

use Mindy\Base\Exception\HttpException;
use Mindy\Base\Mindy;
use Mindy\Helper\Console;
use Mindy\Mail\Mailer;
use Modules\Mail\Models\Mail;
use Modules\Mail\Models\MailTemplate;

class DbMailer extends Mailer
{
    /**
     * @var string http domain
     */
    public $domain;
    /**
     * @var bool enable reading email checker
     */
    public $checker = true;

    public function fromCode($code, $receiver, $data = [])
    {
        $uniq = uniqid();
        $template = $this->loadTemplateModel($code);

        $subject = $this->renderString($template->subject, $data);
        $message = $this->renderString($template->template, $data);
        if ($this->checker && $domain = $this->getDomain()) {
            $url = $domain . Mindy::app()->urlManager->reverse('mail.checker', ['uniq' => $uniq]);
            $message .= "<img src='{$url}'>";
        }

        $msg = $this->compose([
            'text' => "mail/message.txt",
            'html' => "mail/message.html",
        ], array_merge([
            'content' => $message,
            'logoPath' => Mindy::app()->getModule('Mail')->logoPath
        ], $data));
        $msg->setTo($receiver);
        $msg->setFrom(Mindy::app()->managers);
        $msg->setSubject($subject);

        if($result = $msg->send()) {
            $model = new Mail([
                'receiver' => is_array($receiver) ? key($receiver) : $receiver,
                'subject' => $subject,
                'message' => $message,
                'unique_id' => $uniq,
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
            if (YII_DEBUG) {
                throw new HttpException(500, "Mail template with code $code do not exists");
            } else {
                return false;
            }
        }
        return $maildb;
    }
}
