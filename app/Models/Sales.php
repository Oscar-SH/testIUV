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
        'id_employee'
    ];

    public function employee() {
        return $this->belongsTo(Employee::class, 'id_employee');
    }
    
    public function dishes() {
        return $this->belongsToMany(Dishes::class, 'sales_dishes', 'id_sale', 'id_dish');
    }
}
