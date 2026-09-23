<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Booking;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): View
    {
        $appointments = Appointment::with(['booking', 'lawyer', 'client.user'])
            ->latest('appointment_date')->paginate(15);

        return view('cms.appointments.index', compact('appointments'));
    }

    public function create(): View
    {
        $bookings = Booking::all();
        $lawyers = User::where('user_type', 'lawyer')->get();
        $clients = Client::with('user')->get();

        return view('cms.appointments.create', compact('bookings', 'lawyers', 'clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'appointment_date' => 'required|date',
            'status' => 'required|in:مجدول,مكتمل,ملغي',
            'bookings_id' => 'nullable|exists:bookings,id',
            'users_id' => 'required|exists:users,id',
            'clients_id' => 'required|exists:clients,id',
        ]);

        Appointment::create($data);

        return redirect()->route('appointments.index')->with('success', 'تم إنشاء الموعد بنجاح.');
    }

    public function show(Appointment $appointment): View
    {
        $appointment->load(['booking', 'lawyer', 'client.user']);

        return view('cms.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment): View
    {
        $bookings = Booking::all();
        $lawyers = User::where('user_type', 'lawyer')->get();
        $clients = Client::with('user')->get();

        return view('cms.appointments.edit', compact('appointment', 'bookings', 'lawyers', 'clients'));
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $data = $request->validate([
            'appointment_date' => 'required|date',
            'status' => 'required|in:مجدول,مكتمل,ملغي',
            'bookings_id' => 'nullable|exists:bookings,id',
            'users_id' => 'required|exists:users,id',
            'clients_id' => 'required|exists:clients,id',
        ]);

        $appointment->update($data);

        return redirect()->route('appointments.show', $appointment)->with('success', 'تم تحديث الموعد.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'تم حذف الموعد.');
    }
}
