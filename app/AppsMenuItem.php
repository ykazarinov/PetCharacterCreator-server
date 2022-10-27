<?php

namespace App;
use TCG\Voyager\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;



class AppsMenuItem extends Model
{
    //
    use Translatable;
    protected $translatable = ['title'];
}
