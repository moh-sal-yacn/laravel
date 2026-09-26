<?php

namespace App\Http\Controllers;

use App\Http\Requests\Case\StoreCaseRequest;
use App\Http\Requests\Case\UpdateCaseRequest;
use App\Models\Category;
use App\Models\Client;
use App\Models\Court;
use App\Models\CourtCase;
use App\Notifications\CaseCreated;
use App\Notifications\CaseUpdated;
use App\Services\PdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourtCaseController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', CourtCase::class);

        $user = auth()->user();
        $query = CourtCase::with(['client.user', 'court', 'category']);

        // ─── تصفية حسب الدور ───
        if ($user->isLawyer()) {
            $query->whereHas('participants', fn ($q) => $q->where('users_id', $user->id));
        }
        if ($user->isClient()) {
            $query->whereHas('client', fn ($q) => $q->where('users_id', $user->id));
        }

        // ─── الفلاتر المتقدمة ───
        $cases = $query
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(fn ($sub) => $sub->where('case_number', 'like', "%{$search}%")
                                            ->orWhere('case_title', 'like', "%{$search}%"));
            })
            ->when($request->filled('status'), fn ($q) => $q->where('case_status', $request->status))
            ->when($request->filled('court'), fn ($q) => $q->where('courts_id', $request->court))
            ->when($request->filled('category'), fn ($q) => $q->where('categories_id', $request->category))
            ->when($request->filled('client'), fn ($q) => $q->where('clients_id', $request->client))
            ->when($request->filled('opened_from'), fn ($q) => $q->whereDate('opened_at', '>=', $request->opened_from))
            ->when($request->filled('opened_to'), fn ($q) => $q->whereDate('opened_at', '<=', $request->opened_to))
            ->latest('opened_at')
            ->paginate(15)
            ->withQueryString();

        // ─── بيانات الفلاتر ───
        $courts = Court::orderBy('court_name')->get();
        $categories = Category::orderBy('category_name')->get();

        return view('cms.cases.index', compact('cases', 'courts', 'categories'));
    }

    public function create(): View
    {
        $this->authorize('create', CourtCase::class);

        $clients = Client::with('user')->get();
        $courts = Court::all();
        $categories = Category::all();

        return view('cms.cases.create', compact('clients', 'courts', 'categories'));
    }

    public function store(StoreCaseRequest $request): RedirectResponse
    {
        $this->authorize('create', CourtCase::class);

        $case = CourtCase::create($request->validated());

        if (auth()->user()->isLawyer()) {
            $case->participants()->attach(auth()->id(), [
                'role_in_case' => 'محامي أساسي',
                'assigned_at'  => now(),
                'is_active'    => true,
            ]);
        }

        if ($case->client?->user) {
            $case->client->user->notify(new CaseCreated($case));
        }

        return redirect()
            ->route('cases.show', $case)
            ->with('success', 'تم إنشاء القضية بنجاح.');
    }

    public function show(CourtCase $case): View
    {
        $this->authorize('view', $case);

        $case->load([
            'client.user', 'court', 'category', 'participants',
            'sessions.user', 'financialRecords', 'legalPrecedents', 'attachments.uploader',
        ]);

        return view('cms.cases.show', compact('case'));
    }

    public function edit(CourtCase $case): View
    {
        $this->authorize('update', $case);

        $clients = Client::with('user')->get();
        $courts = Court::all();
        $categories = Category::all();

        return view('cms.cases.edit', compact('case', 'clients', 'courts', 'categories'));
    }

    public function update(UpdateCaseRequest $request, CourtCase $case): RedirectResponse
    {
        $this->authorize('update', $case);

        $originalStatus = $case->case_status;
        $case->update($request->validated());

        if ($originalStatus !== $case->case_status && $case->client?->user) {
            $case->client->user->notify(new CaseUpdated(
                $case,
                "تم تحديث حالة القضية من '{$originalStatus}' إلى '{$case->case_status}'"
            ));
        }

        return redirect()
            ->route('cases.show', $case)
            ->with('success', 'تم تحديث بيانات القضية بنجاح.');
    }

    public function destroy(CourtCase $case): RedirectResponse
    {
        $this->authorize('delete', $case);
        $case->delete();

        return redirect()->route('cases.index')->with('success', 'تم حذف القضية بنجاح.');
    }

    public function exportPdf(CourtCase $case)
    {
        $this->authorize('view', $case);

        $case->load([
            'client.user',
            'court',
            'category',
            'participants',
            'sessions.user',
        ]);

        $html = view('pdf.case', compact('case'))->render();

        return PdfService::render(
            $html,
            'case-' . $case->case_number . '.pdf'
        );
    }

    public function trashed(): View
    {
        $cases = CourtCase::onlyTrashed()
            ->with(['client.user', 'court', 'category'])
            ->latest('deleted_at')
            ->paginate(15);

        return view('cms.cases.trashed', compact('cases'));
    }

    public function restore(int $id): RedirectResponse
    {
        $case = CourtCase::onlyTrashed()->findOrFail($id);
        $case->restore();

        return redirect()->route('cases.trashed')
            ->with('success', "تم استرجاع القضية {$case->case_number} بنجاح.");
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $case = CourtCase::onlyTrashed()->findOrFail($id);
        $caseNumber = $case->case_number;
        $case->forceDelete();

        return redirect()->route('cases.trashed')
            ->with('success', "تم حذف القضية {$caseNumber} نهائياً.");
    }
}