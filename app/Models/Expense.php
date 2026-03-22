<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['store_id', 'category', 'amount', 'description', 'date'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
