<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CourtCase;
use App\Models\FinancialRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinancialRecordController extends Controller
{
    public function index(): View
    {
        $records = FinancialRecord::with(['case', 'client.user', 'recordedBy'])
            ->latest('transaction_date')
            ->paginate(20);
        $cases = CourtCase::all();
        $clients = Client::with('user')->get();

        return view('cms.financial_records.index', compact('records', 'cases', 'clients'));
    }

    // Used by the embedded "instant receipt logging form" on the contract show page.
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'transaction_type' => 'required|in:دفعة عقد,رسوم قضية,مصروف,أخرى',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
            'cases_id' => 'nullable|exists:cases,id',
            'clients_id' => 'nullable|exists:clients,id',
        ]);

        $data['users_id'] = $request->user()->id ?? 1;

        FinancialRecord::create($data);

        return back()->with('success', 'تم تسجيل الحركة المالية بنجاح.');
    }

    public function destroy(FinancialRecord $financial_record): RedirectResponse
    {
        $financial_record->delete();

        return back()->with('success', 'تم حذف السجل المالي.');
    }
}
