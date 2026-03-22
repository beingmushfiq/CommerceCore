<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingCampaign extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'name', 'type', 'message', 'recipients_count', 'status'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
