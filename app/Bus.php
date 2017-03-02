<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'route_car';
    
    /**
     * Enable timestamps
     *
     * @var boolean
    */
    public $timestamps = false;
}
