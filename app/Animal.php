<?php

namespace App;
use TCG\Voyager\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    //
    use Translatable;
    protected $translatable = ['animal_title'];
}
