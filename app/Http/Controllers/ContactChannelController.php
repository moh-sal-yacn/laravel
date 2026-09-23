<?php

namespace App\Http\Controllers;

use App\Models\ContactChannel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactChannelController extends Controller
{
    public function index(): View
    {
        $messages = ContactChannel::with('user')->latest('created_at')->paginate(20);

        return view('cms.contact_channels.index', compact('messages'));
    }

    public function update(Request $request, ContactChannel $contact_channel): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:جديد,قيد المعالجة,مغلق',
        ]);

        $contact_channel->update($data);

        return back()->with('success', 'تم تحديث حالة الرسالة.');
    }

    public function destroy(ContactChannel $contact_channel): RedirectResponse
    {
        $contact_channel->delete();

        return back()->with('success', 'تم حذف الرسالة.');
    }
}
