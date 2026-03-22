<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;

class VoiceSearchController extends Controller
{
    /**
     * Process speech-to-text transcript and find relevant products.
     * In a production environment, this would call an LLM (Gemini/OpenAI) 
     * to extract intent. For now, we use a hybrid text-search approach.
     */
    public function search(Request $request, string $storeSlug)
    {
        $store = Store::where('slug', $storeSlug)->firstOrFail();
        $transcript = $request->input('transcript', '');

        if (empty($transcript)) {
            return response()->json(['products' => [], 'message' => 'No audio detected.']);
        }

        // Logic: Extract keywords and find products
        $keywords = explode(' ', strtolower($transcript));
        
        $products = Product::where('store_id', $store->id)
            ->where(function($query) use ($keywords) {
                foreach ($keywords as $word) {
                    if (strlen($word) > 2) {
                        $query->orWhere('name', 'LIKE', "%{$word}%")
                              ->orWhere('description', 'LIKE', "%{$word}%");
                    }
                }
            })
            ->limit(5)
            ->get(['id', 'name', 'price', 'slug', 'image']);

        return response()->json([
            'products' => $products,
            'transcript' => $transcript,
            'match_count' => $products->count()
        ]);
    }
}
