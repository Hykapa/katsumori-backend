<?php

namespace App\Observers;

use App\Contracts\History\History;

class HistoryObserver
{

    /**
     * Handle the User "deleted" event.
     *
     * @param $model
     * @return void
     */
    public function saved($model): void
    {
        app(History::class)->add($model);
    }
}
