<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class buttons extends Model
{
    //
    use Translatable;
    protected $translatable = ['title'];
}
