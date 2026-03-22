<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $fillable = ['store_id', 'name', 'email', 'subject', 'message', 'status'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
