<?php

namespace Modules\Mail\Models;

use Mindy\Orm\Fields\BooleanField;
use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;

class MailTemplate extends Model
{
    public static function getFields()
    {
        return [
            'code' => [
                'class' => CharField::className(),
                'null' => false,
                'unique' => true
            ],
            'subject' => [
                'class' => CharField::className(),
                'null' => false
            ],
            'template' => [
                'class' => TextField::className(),
                'null' => false
            ],
            'is_locked' => [
                'class' => BooleanField::className(),
                'default' => false
            ]
        ];
    }
}
