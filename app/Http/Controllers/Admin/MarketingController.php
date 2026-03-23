<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingCampaign;
use App\Models\User;
use App\Models\Store;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function __construct(private NotificationService $notificationService) {}

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'facebook_pixel_id' => 'nullable|string|max:50',
        ]);

        $store = auth()->user()->store;
        $store->update($validated);

        return back()->with('success', 'Marketing tracking settings updated successfully.');
    }

    public function index()
    {
        $campaigns = MarketingCampaign::latest()->paginate(20);
        return view('admin.marketing.index', compact('campaigns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:sms,email,whatsapp,push',
            'message' => 'required|string',
            'target_rank' => 'nullable|string'
        ]);

        $storeId = session('admin_store_id') ?? Store::first()->id;

        $recipientsQuery = User::where('role', 'customer');
        if ($validated['target_rank']) {
            $recipientsQuery->where('customer_rank', $validated['target_rank']);
        }
        
        $recipients = $recipientsQuery->get()->map(function($user) use ($validated) {
            return [
                'user_id' => $user->id,
                'address' => $validated['type'] === 'email' ? $user->email : ($user->phone ?? 'unknown')
            ];
        });

        $sentCount = $this->notificationService->bulkSend($recipients, $validated['type'], $validated['message']);

        MarketingCampaign::create(array_merge($validated, [
            'store_id' => $storeId,
            'recipients_count' => $sentCount,
            'status' => 'completed'
        ]));

        return back()->with('success', "Campaign '{$validated['name']}' sent to {$sentCount} customers.");
    }
}
