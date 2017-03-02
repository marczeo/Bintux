<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'driver';
    /**
     * Enable timestamps
     *
     * @var boolean
    */
    public $timestamps = false;
}
