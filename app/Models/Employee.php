<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'store_id', 'employee_id', 'designation', 
        'basic_salary', 'joining_date', 'status'
    ];

    protected $casts = [
        'joining_date' => 'date',
        'basic_salary' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}
