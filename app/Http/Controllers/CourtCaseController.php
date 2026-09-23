<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Client;
use App\Models\Court;
use App\Models\CourtCase;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourtCaseController extends Controller
{
    public function index(Request $request): View
    {
        $cases = CourtCase::with(['client.user', 'court', 'category'])
            ->when($request->filled('status'), fn ($q) => $q->where('case_status', $request->status))
            ->latest('opened_at')
            ->paginate(15);

        return view('cms.cases.index', compact('cases'));
    }

    public function create(): View
    {
        $clients = Client::with('user')->get();
        $courts = Court::all();
        $categories = Category::all();

        return view('cms.cases.create', compact('clients', 'courts', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'case_number' => 'required|string|max:45|unique:cases,case_number',
            'case_title' => 'required|string|max:45',
            'case_status' => 'required|in:قيد النظر,مؤجلة,منتهية,مؤرشفة',
            'opened_at' => 'required|date',
            'description' => 'nullable|string',
            'clients_id' => 'required|exists:clients,id',
            'categories_id' => 'nullable|exists:categories,id',
            'courts_id' => 'required|exists:courts,id',
        ]);

        $case = CourtCase::create($data);

        return redirect()->route('cases.show', $case)->with('success', 'تم إنشاء القضية بنجاح.');
    }

    public function show(CourtCase $case): View
    {
        $case->load([
            'client.user',
            'court',
            'category',
            'participants',
            'sessions.user',
            'financialRecords',
            'legalPrecedents',
            'attachments.uploader',
        ]);

        return view('cms.cases.show', compact('case'));
    }

    public function edit(CourtCase $case): View
    {
        $clients = Client::with('user')->get();
        $courts = Court::all();
        $categories = Category::all();

        return view('cms.cases.edit', compact('case', 'clients', 'courts', 'categories'));
    }

    public function update(Request $request, CourtCase $case): RedirectResponse
    {
        $data = $request->validate([
            'case_number' => 'required|string|max:45|unique:cases,case_number,'.$case->id,
            'case_title' => 'required|string|max:45',
            'case_status' => 'required|in:قيد النظر,مؤجلة,منتهية,مؤرشفة',
            'opened_at' => 'required|date',
            'description' => 'nullable|string',
            'clients_id' => 'required|exists:clients,id',
            'categories_id' => 'nullable|exists:categories,id',
            'courts_id' => 'required|exists:courts,id',
        ]);

        $case->update($data);

        return redirect()->route('cases.show', $case)->with('success', 'تم تحديث بيانات القضية.');
    }

    public function destroy(CourtCase $case): RedirectResponse
    {
        $case->delete();

        return redirect()->route('cases.index')->with('success', 'تم حذف القضية.');
    }
}
