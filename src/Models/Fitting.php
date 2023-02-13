<?php

namespace GreyZmeem\Seat\Doctrine\Models;

use Illuminate\Database\Eloquent\Model;

class Fitting extends Model
{
    public $timestamp = true;
    protected $table = 'seat_gz_fitting';
    protected $fillable = ['id', 'name', 'ship', 'shipID', 'fit', 'description'];
    protected $casts = ['fit' => 'array'];
}
