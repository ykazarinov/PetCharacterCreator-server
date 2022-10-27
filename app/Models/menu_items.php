<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class menu_items extends Model
{
    use Translatable;
    protected $translatable = ['title'];
   


    
}
