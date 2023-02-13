<?php

namespace GreyZmeem\Seat\Doctrine\Models;

use Illuminate\Database\Eloquent\Model;

class Doctrine extends Model
{
    public $timestamps = true;
    protected $table = 'seat_gz_doctrine';
    protected $fillable = ['id', 'name', 'description'];

    public function fittings()
    {
        return $this->belongsToMany(Fitting::class, 'seat_gz_doctrine_fitting');
    }
}
