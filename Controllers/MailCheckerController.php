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
        $model = Mail::objects()->filter(['pk' => $id, 'readed_at__isnull' => true])->get();
        if ($model) {
            $model->save(['readed_at']);
        }

        $data = "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP4Xw8AAoABf5/NhYYAAAAASUVORK5CYII=";
        header("Content-type: image/png");
        echo base64_decode($data);
    }
}
