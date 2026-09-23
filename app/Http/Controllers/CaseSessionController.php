<?php

namespace App\Http\Controllers;

use App\Models\CaseSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CaseSessionController extends Controller
{
    // Sessions are managed inline from the case "show" page rather than a
    // dedicated index/create view, so only store/update/destroy are needed here.
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'session_date' => 'required|date',
            'session_notes' => 'nullable|string',
            'next_session_date' => 'nullable|date',
            'cases_id' => 'required|exists:cases,id',
        ]);

        $data['users_id'] = $request->user()->id ?? 1;

        CaseSession::create($data);

        return back()->with('success', 'تمت إضافة الجلسة بنجاح.');
    }

    public function update(Request $request, CaseSession $case_session): RedirectResponse
    {
        $data = $request->validate([
            'session_date' => 'required|date',
            'session_notes' => 'nullable|string',
            'next_session_date' => 'nullable|date',
        ]);

        $case_session->update($data);

        return back()->with('success', 'تم تحديث بيانات الجلسة.');
    }

    public function destroy(CaseSession $case_session): RedirectResponse
    {
        $case_session->delete();

        return back()->with('success', 'تم حذف الجلسة.');
    }
}
