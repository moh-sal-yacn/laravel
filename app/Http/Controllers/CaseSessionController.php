<?php

namespace App\Http\Controllers;

use App\Models\CaseSession;
use App\Notifications\CaseSessionReminder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CaseSessionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'session_date'      => 'required|date',
            'session_notes'     => 'nullable|string',
            'next_session_date' => 'nullable|date',
            'cases_id'          => 'required|exists:cases,id',
        ]);

        $data['users_id'] = $request->user()->id ?? 1;

        $session = CaseSession::create($data);

        // ✅ إشعار أطراف القضية
        $case = $session->case;
        if ($case) {
            $notifyUsers = collect();

            if ($session->user) {
                $notifyUsers->push($session->user);
            }
            if ($case->client?->user) {
                $notifyUsers->push($case->client->user);
            }
            foreach ($case->participants as $participant) {
                $notifyUsers->push($participant);
            }

            $notifyUsers->unique('id')->each(function ($user) use ($session) {
                $user->notify(new CaseSessionReminder($session));
            });
        }

        return back()->with('success', 'تمت إضافة الجلسة بنجاح.');
    }

    public function update(Request $request, CaseSession $case_session): RedirectResponse
    {
        $data = $request->validate([
            'session_date'      => 'required|date',
            'session_notes'     => 'nullable|string',
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