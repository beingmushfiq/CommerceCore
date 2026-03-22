<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BuilderSection extends Model
{
    protected $fillable = ['page_id', 'type', 'position', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(BuilderPage::class, 'page_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(BuilderContent::class, 'section_id');
    }

    public function getContent(string $key, $default = ''): string
    {
        $content = $this->contents->firstWhere('key', $key);
        return $content ? $content->value : $default;
    }

    public static function sectionTypes(): array
    {
        return [
            'hero' => ['label' => 'Hero Section', 'icon' => 'sparkles', 'description' => 'Full-width hero with headline, subtitle, and CTA button'],
            'product_grid' => ['label' => 'Product Grid', 'icon' => 'squares-2x2', 'description' => 'Display featured products in a grid layout'],
            'banner' => ['label' => 'Banner', 'icon' => 'photo', 'description' => 'Full-width image banner with overlay text'],
            'text_block' => ['label' => 'Text Block', 'icon' => 'document-text', 'description' => 'Rich text content section'],
            'cta' => ['label' => 'Call to Action', 'icon' => 'cursor-arrow-rays', 'description' => 'Conversion-focused section with prominent CTA'],
        ];
    }

    public static function defaultContent(string $type): array
    {
        return match($type) {
            'hero' => [
                'title' => 'Welcome to Our Store',
                'subtitle' => 'Discover amazing products at great prices',
                'button_text' => 'Shop Now',
                'button_url' => '#products',
                'image' => '',
                'overlay_color' => 'rgba(0,0,0,0.4)',
            ],
            'product_grid' => [
                'title' => 'Featured Products',
                'subtitle' => 'Handpicked just for you',
                'count' => '8',
            ],
            'banner' => [
                'title' => 'Special Offer',
                'subtitle' => 'Up to 50% off on selected items',
                'image' => '',
                'button_text' => 'View Deals',
                'button_url' => '#',
            ],
            'text_block' => [
                'title' => 'About Us',
                'content' => 'We are passionate about delivering the best products and service to our customers.',
            ],
            'cta' => [
                'title' => 'Ready to get started?',
                'subtitle' => 'Join thousands of happy customers',
                'button_text' => 'Get Started',
                'button_url' => '#',
            ],
            default => [],
        };
    }
}
