<?php

namespace Modules\Mail;

use Mindy\Base\Mindy;
use Mindy\Base\Module;

class MailModule extends Module
{
    /**
     * Path to logo
     * @var string
     */
    public $logoPath;

    /**
     * Convert icon to base64
     * @var bool
     */
    public $convert64 = false;

    public function getMenu()
    {
        return [
            'name' => $this->getName(),
            'items' => [
                [
                    'name' => self::t('Mail templates'),
                    'adminClass' => 'MailTemplateAdmin',
                    'icon' => 'icon-mail'
                ],
                [
                    'name' => self::t('Mail'),
                    'adminClass' => 'MailAdmin',
                    'icon' => 'icon-mail'
                ],
                [
                    'name' => self::t('Subscribes'),
                    'adminClass' => 'SubscribeAdmin',
                    'icon' => 'icon-mail'
                ]
            ]
        ];
    }
}
