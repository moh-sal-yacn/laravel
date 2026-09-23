<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContractController extends Controller
{
    public function index(Request $request): View
    {
        $contracts = Contract::with(['client.user', 'lawyer'])
            ->when($request->filled('status'), fn ($q) => $q->where('contract_status', $request->status))
            ->latest('signed_at')
            ->paginate(15);

        return view('cms.contracts.index', compact('contracts'));
    }

    public function create(): View
    {
        $clients = Client::with('user')->get();
        $lawyers = User::where('user_type', 'lawyer')->get();

        return view('cms.contracts.create', compact('clients', 'lawyers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'contract_type' => 'required|string|max:45',
            'contract_status' => 'required|in:نشط,منتهي,ملغي',
            'parties' => 'required|string',
            'contract_value' => 'required|numeric|min:0',
            'signed_at' => 'required|date',
            'clients_id' => 'required|exists:clients,id',
            'users_id' => 'required|exists:users,id',
        ]);

        $contract = Contract::create($data);

        return redirect()->route('contracts.show', $contract)->with('success', 'تم إنشاء العقد بنجاح.');
    }

    public function show(Contract $contract): View
    {
        $contract->load(['client.user', 'lawyer', 'attachments.uploader']);

        $payments = $contract->payments()->latest('transaction_date')->get();
        $totalPaid = $payments->sum('amount');
        $remaining = $contract->contract_value - $totalPaid;

        return view('cms.contracts.show', compact('contract', 'payments', 'totalPaid', 'remaining'));
    }

    public function edit(Contract $contract): View
    {
        $clients = Client::with('user')->get();
        $lawyers = User::where('user_type', 'lawyer')->get();

        return view('cms.contracts.edit', compact('contract', 'clients', 'lawyers'));
    }

    public function update(Request $request, Contract $contract): RedirectResponse
    {
        $data = $request->validate([
            'contract_type' => 'required|string|max:45',
            'contract_status' => 'required|in:نشط,منتهي,ملغي',
            'parties' => 'required|string',
            'contract_value' => 'required|numeric|min:0',
            'signed_at' => 'required|date',
            'clients_id' => 'required|exists:clients,id',
            'users_id' => 'required|exists:users,id',
        ]);

        $contract->update($data);

        return redirect()->route('contracts.show', $contract)->with('success', 'تم تحديث بيانات العقد.');
    }

    public function destroy(Contract $contract): RedirectResponse
    {
        $contract->delete();

        return redirect()->route('contracts.index')->with('success', 'تم حذف العقد.');
    }
}
