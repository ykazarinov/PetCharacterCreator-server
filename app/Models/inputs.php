<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class inputs extends Model
{
    //
    use Translatable;
    protected $translatable = ['label', 'placeholder'];
}
