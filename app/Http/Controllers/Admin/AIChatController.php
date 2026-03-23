<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AI\ChatQueryEngine;
use App\Models\AiInsight;
use App\Models\SystemAlert;
use App\Models\Store;

class AIChatController extends Controller
{
    /**
     * Show the AI Chat panel (can be embedded as a widget).
     */
    public function index()
    {
        $store = $this->getActiveStore();
        
        $insights = AiInsight::where('store_id', $store->id)
            ->where('status', 'new')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $alerts = SystemAlert::where('store_id', $store->id)
            ->where('status', 'active')
            ->orderByDesc('severity')
            ->take(5)
            ->get();

        return view('admin.ai.chat', compact('insights', 'alerts'));
    }

    /**
     * Handle a chat message from the admin.
     */
    public function ask(Request $request, ChatQueryEngine $engine)
    {
        $request->validate(['question' => 'required|string|max:500']);

        $store = $this->getActiveStore();
        $result = $engine->ask($request->input('question'), $store->id);

        return response()->json($result);
    }

    /**
     * Dismiss an AI insight.
     */
    public function dismissInsight(AiInsight $insight)
    {
        $insight->dismiss();
        return back()->with('success', 'Insight dismissed.');
    }

    /**
     * Resolve a system alert.
     */
    public function resolveAlert(SystemAlert $alert)
    {
        $alert->resolve();
        return back()->with('success', 'Alert resolved.');
    }

    /**
     * Get the active store for the current user.
     */
    protected function getActiveStore(): Store
    {
        if (app()->has('current_tenant_id')) {
            return Store::findOrFail(app('current_tenant_id'));
        }
        return auth()->user()->stores()->firstOrFail();
    }
}
