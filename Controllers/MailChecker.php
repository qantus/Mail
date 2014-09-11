<?php

namespace Modules\Mail\Controllers;

// TODO
use ImageHelper;

use Modules\Core\Controllers\FrontendController;
use Modules\Mail\Models\Mail;

class MailChecker extends FrontendController
{
    public $defaultAction = 'checker';

    public function allowedActions()
    {
        return ['checker'];
    }

    public function actionChecker($id)
    {
        $model = Mail::objects()->filter(['pk' => $id])->get();
        if($model === null) {
            return false;
        } else {
            $model->save();
            return ImageHelper::createBlankImage(1, 1);
        }
    }
}
