<?php

namespace App\Http\Controllers;

use App\Models\CourtCase;
use App\Models\LegalPrecedent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalPrecedentController extends Controller
{
    /**
     * عرض قائمة السوابق مع الفلاتر
     */
    public function index(Request $request): View
    {
        $precedents = LegalPrecedent::with('case')
            // ─── الفلاتر ───
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(fn ($sub) => $sub->where('title', 'like', "%{$search}%")
                                            ->orWhere('summary', 'like', "%{$search}%"));
            })
            ->when($request->filled('source'), fn ($q) => $q->where('source', $request->source))
            ->when($request->filled('case'), fn ($q) => $q->where('cases_id', $request->case))
            ->when($request->filled('ruling_from'), fn ($q) => $q->whereDate('ruling_date', '>=', $request->ruling_from))
            ->when($request->filled('ruling_to'), fn ($q) => $q->whereDate('ruling_date', '<=', $request->ruling_to))
            ->latest('ruling_date')
            ->paginate(15)
            ->withQueryString();

        $cases = CourtCase::orderBy('case_number')->get();

        return view('cms.legal_precedents.index', compact('precedents', 'cases'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'source' => 'required|in:محكمة النقض,محكمة الاستئناف,محكمة عليا,أخرى',
            'title' => 'required|string|max:45',
            'summary' => 'required|string',
            'external_link' => 'nullable|url',
            'ruling_date' => 'required|date',
            'cases_id' => 'nullable|exists:cases,id',
        ]);

        LegalPrecedent::create($data);

        return redirect()->route('legal-precedents.index')->with('success', 'تمت إضافة السابقة القضائية.');
    }

    public function destroy(LegalPrecedent $legal_precedent): RedirectResponse
    {
        $legal_precedent->delete();

        return redirect()->route('legal-precedents.index')->with('success', 'تم حذف السابقة القضائية.');
    }
}