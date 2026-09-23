<?php

namespace App\Http\Controllers;

use App\Models\FeaturedCompany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeaturedCompanyController extends Controller
{
    public function index(): View
    {
        $companies = FeaturedCompany::latest()->paginate(15);

        return view('cms.featured_companies.index', compact('companies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:45',
            'contract_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        FeaturedCompany::create($data);

        return redirect()->route('featured-companies.index')->with('success', 'تمت إضافة الشركة بنجاح.');
    }

    public function destroy(FeaturedCompany $featured_company): RedirectResponse
    {
        $featured_company->delete();

        return redirect()->route('featured-companies.index')->with('success', 'تم حذف الشركة.');
    }
}
