<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\LogsActivity;

class Store extends Model
{
    use LogsActivity;
    protected $fillable = [
        'owner_id', 'name', 'slug', 'domain', 'description', 'logo', 'status', 'plan_id',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(BuilderPage::class);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(StoreSetting::class);
    }

    public function staff(): HasMany
    {
        return $this->hasMany(User::class, 'store_id');
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getThemeConfig(): array
    {
        $settings = $this->settings;
        if ($settings && $settings->theme) {
            return array_merge(
                $settings->theme->config_json ?? [],
                $settings->settings_json ?? []
            );
        }
        return $settings->settings_json ?? [];
    }
}
