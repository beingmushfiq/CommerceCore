<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;

class CRMController extends Controller
{
    public function subscribers(Request $request)
    {
        $store = $request->get('admin_store');
        $subscribers = NewsletterSubscriber::where('store_id', $store->id)
            ->latest()
            ->paginate(20);

        return view('admin.crm.subscribers', compact('subscribers'));
    }

    public function inquiries(Request $request)
    {
        $store = $request->get('admin_store');
        $inquiries = ContactSubmission::where('store_id', $store->id)
            ->latest()
            ->paginate(20);

        return view('admin.crm.inquiries', compact('inquiries'));
    }

    public function updateInquiryStatus(Request $request, ContactSubmission $inquiry)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:new,read,replied,closed'
        ]);

        $inquiry->update($validated);

        return redirect()->back()->with('success', 'Inquiry status updated!');
    }
}
