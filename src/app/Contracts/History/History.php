<?php

namespace App\Contracts\History;

use Illuminate\Database\Eloquent\Model;

interface History
{
    public function add(Model $model);
}
