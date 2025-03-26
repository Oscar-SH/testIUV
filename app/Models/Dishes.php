<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dishes extends Model
{
    use SoftDeletes;

    protected $table = 'dishes';

    protected $primary_key = 'id';

    protected $fillable = [
        'name',
        'description',
        'make_time',
        'make_price',
        'sale_price',
        'status'
    ];

    public function sales() {
        return $this->belongsToMany(Sales::class, 'sales_dishes', 'id_dish', 'id_sale');
    }
}
