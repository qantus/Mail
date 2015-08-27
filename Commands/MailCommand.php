<?php

namespace Modules\Mail\Commands;

use Exception;
use Mindy\Base\Mindy;
use Mindy\Console\ConsoleCommand;
use Mindy\Helper\Creator;
use Mindy\Utils\RenderTrait;
use Modules\Core\Components\ParamsHelper;
use Modules\Mail\Models\Mail;
use Modules\Mail\Models\Queue;
use Modules\Mail\Models\QueueItem;

/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 28/04/14.04.2014 17:53
 */
class MailCommand extends ConsoleCommand
{
    use RenderTrait;

    public function actionIndex($to, $from = 'admin@admin.com', $template = "mail/test")
    {
        echo "Sending test mail to " . $to . PHP_EOL;

        $data = [];
        $status = Mindy::app()->mail->compose([
            'text' => $template . ".txt",
            'html' => $template . ".html"
        ], $data)->setTo($to)->setFrom($from)->setSubject("Test mail")->send();
        echo ($status ? "Success" : "Failed") . PHP_EOL;
    }

    public function actionStartQueue($domain)
    {
        $perCycle = ParamsHelper::get('mail.queue.per_cycle_items_count', 15);

        $urlManager = Mindy::app()->urlManager;

        Mail::objects()->truncate();
        Queue::objects()->filter(['pk' => 1])->update(['is_running' => false]);

        $queues = Queue::objects()->filter(['is_running' => false])->incomplete()->all();
        foreach ($queues as $q) {
            $data = unserialize($q->data);
            $data = is_array($data) ? $data : [];

            foreach ($q->subscribers->batch(100) as $subscribers) {
                foreach ($subscribers as $subscriber) {
                    $uniqueId = md5($subscriber->email . $q->created_at);
                    $url = $domain . $urlManager->reverse('mail:checker', ['id' => $uniqueId]);
                    $checker = strtr("<img style='width: 1px !important; height: 1px !important;' src='{url}'>", [
                        '{url}' => $url
                    ]);
                    $attributes = [
                        'queue' => $q,
                        'subject' => $this->renderString($q->subject, $data),
                        'message_txt' => $this->renderTemplate($q->template . ".txt", [
                            'content' => $this->renderString($q->message, $data),
                        ]),
                        'message_html' => $this->renderTemplate($q->template . ".html", [
                            'content' => $this->renderString($q->message, $data) . $checker,
                        ]),
                        'email' => $subscriber->email,
                        'unique_id' => $uniqueId
                    ];

                    $item = new Mail($attributes);
                    if ($item->save() === false) {
                        throw new Exception("Failed to save QueueItem model");
                    }
                }
            }
            $q->is_running = true;
            $q->save(['is_running']);
        }

        $qb = Mindy::app()->db->getDb()->getQueryBuilder();
        $queueItems = Mail::objects()->filter([
            'is_sended' => false
        ])->limit($perCycle)->offset(0)->order(['-id'])->all();

        foreach ($queueItems as $item) {
            list($sended, $error) = $item->send();
            if ($sended) {
                $item->is_sended = date($qb->dateTimeFormat);
                $item->save(['is_sended']);
            } else {
                $item->error = $error;
                $item->save(['error']);
            }
        }
    }
}
