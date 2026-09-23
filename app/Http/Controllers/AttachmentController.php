<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    // Backs the drag-and-drop uploader on the case/contract show pages.
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'file' => 'required|file|max:10240',
            'related_id' => 'required|integer',
            'related_type' => 'required|in:case,contract,user',
        ]);

        $path = $request->file('file')->store('attachments', 'public');

        Attachment::create([
            'related_id' => $data['related_id'],
            'related_type' => $data['related_type'],
            'file_url' => $path,
            'file_type' => $request->file('file')->extension(),
            'uploaded_at' => now(),
            'users_id' => $request->user()->id ?? 1,
        ]);

        return back()->with('success', 'تم رفع المستند بنجاح.');
    }

    public function destroy(Attachment $attachment): RedirectResponse
    {
        $attachment->delete();

        return back()->with('success', 'تم حذف المستند.');
    }
}
