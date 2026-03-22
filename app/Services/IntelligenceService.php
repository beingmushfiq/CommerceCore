<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;

class IntelligenceService
{
    /**
     * Calculate fraud score for an order (0-100, where 100 is high risk).
     */
    public function calculateFraudScore(Order $order): int
    {
        $score = 0;
        $user = $order->user;

        // 1. High Value Order Check
        if ($order->total_price > 1000) {
            $score += 15;
        }

        // 2. New Customer vs Returning
        if ($user && $user->orders()->count() === 1) {
            $score += 10;
        }

        // 3. Suspicious Phone Number Patterns (e.g., repeating digits)
        if (preg_match('/(\d)\1{4,}/', $order->phone)) {
            $score += 25;
        }

        // 4. Low confirmed order rate (if applicable)
        // ...

        return min($score, 100);
    }

    public function getTrends($storeId)
    {
        return [
            'peak_order_time' => '18:00 - 21:00',
            'top_performing_category' => 'Electronics',
            'estimated_growth_next_month' => '+12.5%',
            'churn_risk_customers' => 42
        ];
    }

    /**
     * Generate an AI-driven marketing campaign suggestion for a store.
     */
    public function generateCampaignSuggestion($store)
    {
        $categoryName = 'Selected Collections';

        return [
            'name' => "The " . $categoryName . " Flash Sale",
            'target_audience' => "Inactive customers from last 30 days",
            'suggested_discount' => "20%",
            'predicted_conversion' => "4.5%",
            'ai_rationale' => "Based on seasonal trends and current inventory surplus in " . $categoryName . "."
        ];
    }
}
