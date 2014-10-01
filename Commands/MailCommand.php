<?php

namespace Modules\Mail\Commands;

use Mindy\Console\ConsoleCommand;
use Mindy\Helper\Creator;

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
    public function actionIndex($to, $from = 'admin@admin.com', $template = "mail/test")
    {
        echo "Sending test mail to " . $to . PHP_EOL;

        $data = [];
        $mail = Creator::createObject([
            'class' => '\Mindy\Mail\Mailer',
        ]);
        $status = $mail->compose([
            'text' => $template . ".txt",
            'html' => $template . ".html"
        ], $data)->setTo($to)->setFrom($from)->setSubject("Test mail")->send();
        echo ($status ? "Success" : "Failed") . PHP_EOL;
    }
}
