<?php

namespace Modules\Mail\Commands;

use Mindy\Base\ConsoleCommand;
use Mindy\Base\Mindy;
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
    public function actionIndex($to, $template = null)
    {
        echo $this->color("Sending test mail to " . $to, 'green') . PHP_EOL;

        $data = [];
        $mail = Creator::createObject([
            'class' => '\Mindy\Mail\Mailer',
            'useFileTransport' => true
        ]);
        $mail->compose([
            'text' => "mail/test.txt",
            'html' => "mail/test.html"
        ], $data)->setTo($to)->setSubject("Test mail")->send();
    }
}
