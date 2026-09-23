<?php

namespace App\Http\Controllers;

use App\Models\CourtCase;
use App\Models\LegalPrecedent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalPrecedentController extends Controller
{
    public function index(): View
    {
        $precedents = LegalPrecedent::with('case')->latest('ruling_date')->paginate(15);
        $cases = CourtCase::all();

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
