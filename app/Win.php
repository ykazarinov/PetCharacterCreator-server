<?php

namespace App;

use TCG\Voyager\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Win extends Model
{
    //

    use Translatable;
    protected $translatable = ['title'];
}
