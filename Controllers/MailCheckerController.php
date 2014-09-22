<?php

namespace Modules\Mail\Controllers;

use Modules\Core\Controllers\CoreController;
use Modules\Mail\Models\Mail;

class MailCheckerController extends CoreController
{
    public function allowedActions()
    {
        return ['index'];
    }

    public function actionIndex($id)
    {
        $model = Mail::objects()->filter(['pk' => $id])->get();
        if($model) {
            $model->save();
        }

        $data = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQYV2NgYAAAAAMAAWgmWQ0AAAAASUVORK5CYII=";
        header("Content-type: image/png");
        echo base64_decode($data);
    }
}
