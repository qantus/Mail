<?php

namespace Modules\Mail\Models;

use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\DateTimeField;
use Mindy\Orm\Fields\EmailField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;
use Modules\Mail\MailModule;

class Mail extends Model
{
    public static function getFields()
    {
        return [
            'receiver' => [
                'class' => EmailField::className(),
                'verboseName' => MailModule::t('Receiver')
            ],
            'subject' => [
                'class' => CharField::className(),
                'verboseName' => MailModule::t('Subject')
            ],
            'message' => [
                'class' => TextField::className(),
                'verboseName' => MailModule::t('Message')
            ],
            'created_at' => [
                'class' => DateTimeField::className(),
                'autoNowAdd' => true,
                'verboseName' => MailModule::t('Created at')
            ],
            'readed_at' => [
                'class' => DateTimeField::className(),
                'autoNow' => true,
                'verboseName' => MailModule::t('Readed at')
            ]
        ];
    }

    public function __toString()
    {
        return (string)strtr("{receiver}: {subject}", [
            '{receiver}' => $this->receiver,
            '{subject}' => $this->subject,
        ]);
    }

    public function getAdminNames($instance = null)
    {
        $names = parent::getAdminNames($instance);
        $names[0] = $this->getModule()->t('Mail');
        return $names;
    }
}
