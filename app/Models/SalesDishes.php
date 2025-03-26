<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesDishes extends Model
{
    use SoftDeletes;

    protected $table = 'sales_dishes';

    protected $primary_key = 'id';

    protected $fillable = [
        'id_sale',
        'id_dish'
    ];
}
