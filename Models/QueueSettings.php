<?php

/**
 * User: max
 * Date: 27/08/15
 * Time: 18:20
 */

namespace Modules\Mail\Models;

use Mindy\Orm\Fields\EmailField;
use Mindy\Orm\Fields\IntField;
use Modules\Core\Models\SettingsModel;
use Modules\Mail\MailModule;

class QueueSettings extends SettingsModel
{
    public static function getFields()
    {
        return [
            'per_cycle_items_count' => [
                'class' => IntField::className(),
                'verboseName' => MailModule::t('Count items for batch sending'),
                'helpText' => MailModule::t('Count items for sending in one iteration. Default value: 15'),
                'default' => 15
            ],
            'from' => [
                'class' => EmailField::className(),
                'verboseName' => MailModule::t('From field in email'),
                'helpText' => MailModule::t('Default value: admin@example.com'),
                'default' => 'admin@example.com'
            ]
        ];
    }
}
