<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $bookings = Booking::with(['lawyer', 'client.user'])->latest('preferred_date')->paginate(15);

        return view('cms.bookings.index', compact('bookings'));
    }

    public function create(): View
    {
        $lawyers = User::where('user_type', 'lawyer')->get();
        $clients = Client::with('user')->get();

        return view('cms.bookings.create', compact('lawyers', 'clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'visitor_name' => 'required|string|max:45',
            'booking_type' => 'required|in:استشارة,متابعة قضية,توقيع عقد,أخرى',
            'preferred_date' => 'required|date',
            'status' => 'required|in:قيد الانتظار,مؤكد,ملغي,مكتمل',
            'users_id' => 'nullable|exists:users,id',
            'clients_id' => 'nullable|exists:clients,id',
        ]);

        Booking::create($data);

        return redirect()->route('bookings.index')->with('success', 'تم إنشاء الحجز بنجاح.');
    }

    public function show(Booking $booking): View
    {
        $booking->load(['lawyer', 'client.user', 'appointments']);

        return view('cms.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking): View
    {
        $lawyers = User::where('user_type', 'lawyer')->get();
        $clients = Client::with('user')->get();

        return view('cms.bookings.edit', compact('booking', 'lawyers', 'clients'));
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'visitor_name' => 'required|string|max:45',
            'booking_type' => 'required|in:استشارة,متابعة قضية,توقيع عقد,أخرى',
            'preferred_date' => 'required|date',
            'status' => 'required|in:قيد الانتظار,مؤكد,ملغي,مكتمل',
            'users_id' => 'nullable|exists:users,id',
            'clients_id' => 'nullable|exists:clients,id',
        ]);

        $booking->update($data);

        return redirect()->route('bookings.show', $booking)->with('success', 'تم تحديث الحجز.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'تم حذف الحجز.');
    }
}
