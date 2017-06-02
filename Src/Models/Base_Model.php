<?php

namespace K_Laravel_Creator\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Base_Model extends Model implements Jsonable{

    use SoftDeletes;

    public $superior =null;
    protected $guarded = [];

}

