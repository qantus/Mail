<?php

/**
 * User: max
 * Date: 27/08/15
 * Time: 20:33
 */

namespace Modules\Mail\Forms;

use Mindy\Form\Fields\UEditorField;
use Mindy\Form\ModelForm;

class QueueForm extends ModelForm
{
    public function getFields()
    {
        return [
            'message' => [
                'class' => UEditorField::className()
            ]
        ];
    }
}
