<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model {
    protected $fillable = ['truck_id', 'driver_name', 'status'];
}
