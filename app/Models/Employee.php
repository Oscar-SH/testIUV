<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $table = 'employees';

    protected $primary_key = 'id';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'position',
        'nss',
        'rfc',
        'salary',
        'sucursal'
    ];

    public function sales() {
        return $this->hasMany(Sales::class, 'id_employee');
    }
    
}
