<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Client;
use App\Models\CourtCase;
use App\Models\ServiceRating;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceRatingController extends Controller
{
    public function index(): View
    {
        $ratings = ServiceRating::with(['client.user', 'category', 'case', 'booking'])
            ->latest('created_at')->paginate(20);
        $clients = Client::with('user')->get();
        $categories = Category::all();
        $cases = CourtCase::all();
        $bookings = Booking::all();

        return view('cms.service_ratings.index', compact('ratings', 'clients', 'categories', 'cases', 'bookings'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'clients_id' => 'required|exists:clients,id',
            'categories_id' => 'nullable|exists:categories,id',
            'cases_id' => 'nullable|exists:cases,id',
            'bookings_id' => 'nullable|exists:bookings,id',
        ]);

        ServiceRating::create($data);

        return back()->with('success', 'شكرًا لتقييمكم.');
    }

    public function destroy(ServiceRating $service_rating): RedirectResponse
    {
        $service_rating->delete();

        return back()->with('success', 'تم حذف التقييم.');
    }
}
