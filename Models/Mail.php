<?php

namespace Modules\Mail\Models;

use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\DateTimeField;
use Mindy\Orm\Fields\EmailField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;

class Mail extends Model
{
    public static function getFields()
    {
        return [
            'receiver' => [
                'class' => EmailField::className()
            ],
            'subject' => [
                'class' => CharField::className()
            ],
            'message' => [
                'class' => TextField::className()
            ],
            'created_at' => [
                'class' => DateTimeField::className(),
                'autoNowAdd' => true
            ],
            'readed_at' => [
                'class' => DateTimeField::className(),
                'autoNow' => true
            ]
        ];
    }
}
