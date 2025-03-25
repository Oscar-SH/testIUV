<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use SoftDeletes;

    protected $table = 'sales';

    protected $primary_key = 'id';

    protected $fillable = [
        'table_number',
        'tip',
        'id_employee',
        'id_dish'
    ];

    public function employee(){
        return $this->belongsTo(Employee::class, 'id_employee', 'id');
    }

    public function dish(){
        return $this->belongsTo(Dishes::class, 'id_dish', 'id');
    }
}
