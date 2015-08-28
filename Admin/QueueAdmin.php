<?php

/**
 * User: max
 * Date: 27/08/15
 * Time: 17:49
 */

namespace Modules\Mail\Admin;

use Modules\Admin\Components\ModelAdmin;
use Modules\Mail\Models\Queue;

class QueueAdmin extends ModelAdmin
{
    public function getColumns()
    {
        return ['name', 'subject', 'created_at', 'user', 'count', 'is_complete', 'started_at', 'stopped_at'];
    }

    public function getModel()
    {
        return new Queue;
    }
}
