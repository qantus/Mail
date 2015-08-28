<?php

/**
 * User: max
 * Date: 27/08/15
 * Time: 17:49
 */

namespace Modules\Mail\Admin;

use Modules\Admin\Components\ModelAdmin;
use Modules\Mail\Models\QueueItem;

class QueueItemAdmin extends ModelAdmin
{
    public function getModel()
    {
        return new QueueItem;
    }
}
