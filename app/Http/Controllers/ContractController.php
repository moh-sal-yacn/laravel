<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contract\StoreContractRequest;
use App\Http\Requests\Contract\UpdateContractRequest;
use App\Models\Client;
use App\Models\Contract;
use App\Models\User;
use App\Notifications\ContractSigned;
use App\Services\PdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContractController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Contract::class);

        $user = auth()->user();
        $query = Contract::with(['client.user', 'lawyer']);

        // ─── تصفية حسب الدور ───
        if ($user->isClient()) {
            $query->whereHas('client', fn ($q) => $q->where('users_id', $user->id));
        }
        if ($user->isLawyer()) {
            $query->where('users_id', $user->id);
        }

        // ─── الفلاتر المتقدمة ───
        $contracts = $query
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(fn ($sub) => $sub->where('contract_type', 'like', "%{$search}%")
                                            ->orWhere('parties', 'like', "%{$search}%"));
            })
            ->when($request->filled('status'), fn ($q) => $q->where('contract_status', $request->status))
            ->when($request->filled('client'), fn ($q) => $q->where('clients_id', $request->client))
            ->when($request->filled('lawyer'), fn ($q) => $q->where('users_id', $request->lawyer))
            ->when($request->filled('value_min'), fn ($q) => $q->where('contract_value', '>=', $request->value_min))
            ->when($request->filled('value_max'), fn ($q) => $q->where('contract_value', '<=', $request->value_max))
            ->when($request->filled('signed_from'), fn ($q) => $q->whereDate('signed_at', '>=', $request->signed_from))
            ->when($request->filled('signed_to'), fn ($q) => $q->whereDate('signed_at', '<=', $request->signed_to))
            ->latest('signed_at')
            ->paginate(15)
            ->withQueryString();

        // ─── بيانات الفلاتر ───
        $clients = Client::with('user')->orderBy('id')->get();
        $lawyers = User::where('user_type', 'lawyer')->orderBy('name')->get();

        return view('cms.contracts.index', compact('contracts', 'clients', 'lawyers'));
    }

    public function create(): View
    {
        $this->authorize('create', Contract::class);

        $clients = Client::with('user')->get();
        $lawyers = User::where('user_type', 'lawyer')->get();

        return view('cms.contracts.create', compact('clients', 'lawyers'));
    }

    public function store(StoreContractRequest $request): RedirectResponse
    {
        $this->authorize('create', Contract::class);

        $contract = Contract::create($request->validated());

        if ($contract->client?->user) {
            $contract->client->user->notify(new ContractSigned($contract));
        }

        return redirect()
            ->route('contracts.show', $contract)
            ->with('success', 'تم إنشاء العقد بنجاح.');
    }

    public function show(Contract $contract): View
    {
        $this->authorize('view', $contract);

        $contract->load(['client.user', 'lawyer', 'attachments.uploader']);

        $payments = $contract->payments()->latest('transaction_date')->get();
        $totalPaid = $payments->sum('amount');
        $remaining = $contract->contract_value - $totalPaid;

        return view('cms.contracts.show', compact('contract', 'payments', 'totalPaid', 'remaining'));
    }

    public function edit(Contract $contract): View
    {
        $this->authorize('update', $contract);

        $clients = Client::with('user')->get();
        $lawyers = User::where('user_type', 'lawyer')->get();

        return view('cms.contracts.edit', compact('contract', 'clients', 'lawyers'));
    }

    public function update(UpdateContractRequest $request, Contract $contract): RedirectResponse
    {
        $this->authorize('update', $contract);
        $contract->update($request->validated());

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'تم تحديث بيانات العقد بنجاح.');
    }

    public function destroy(Contract $contract): RedirectResponse
    {
        $this->authorize('delete', $contract);
        $contract->delete();

        return redirect()->route('contracts.index')->with('success', 'تم حذف العقد بنجاح.');
    }

    public function exportPdf(Contract $contract)
    {
        $this->authorize('view', $contract);

        $contract->load(['client.user', 'lawyer']);

        $html = view('pdf.contract', compact('contract'))->render();

        return PdfService::render(
            $html,
            'contract-' . $contract->id . '.pdf'
        );
    }

    public function exportInvoice(Contract $contract)
    {
        $this->authorize('view', $contract);

        $contract->load(['client.user', 'lawyer']);

        $payments = $contract->payments()->latest('transaction_date')->get();
        $totalPaid = $payments->sum('amount');
        $remaining = $contract->contract_value - $totalPaid;

        $html = view('pdf.invoice', compact('contract', 'payments', 'totalPaid', 'remaining'))->render();

        return PdfService::render(
            $html,
            'invoice-' . $contract->id . '.pdf'
        );
    }

    public function trashed(): View
    {
        $contracts = Contract::onlyTrashed()
            ->with(['client.user', 'lawyer'])
            ->latest('deleted_at')
            ->paginate(15);

        return view('cms.contracts.trashed', compact('contracts'));
    }

    public function restore(int $id): RedirectResponse
    {
        $contract = Contract::onlyTrashed()->findOrFail($id);
        $contract->restore();

        return redirect()->route('contracts.trashed')->with('success', 'تم استرجاع العقد بنجاح.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $contract = Contract::onlyTrashed()->findOrFail($id);
        $contract->forceDelete();

        return redirect()->route('contracts.trashed')->with('success', 'تم حذف العقد نهائياً.');
    }
}