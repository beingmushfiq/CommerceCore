<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['store_id', 'name', 'address', 'phone', 'email', 'is_primary'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function inventories()
    {
        return $this->hasMany(BranchInventory::class);
    }
}
