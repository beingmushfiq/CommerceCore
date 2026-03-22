<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class BlockedPhone extends Model
{
    use LogsActivity;

    protected $fillable = [
        'store_id', 'phone_number', 'reason', 'blocked_by'
    ];
}
