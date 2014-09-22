<?php

namespace Modules\Mail;

use Mindy\Base\Module;

class MailModule extends Module
{
    // public $logoPath = '/static/dist/images/main/logo.png';
    public $logoPath;

    public function getMenu()
    {
        return [
            'name' => $this->getName(),
            'items' => [
                [
                    'name' => self::t('Mail templates'),
                    'adminClass' => 'MailTemplateAdmin',
                    'icon' => 'icon-mail'
                ]
            ]
        ];
    }
}
