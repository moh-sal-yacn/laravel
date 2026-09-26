<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Booking;
use App\Models\Client;
use App\Models\User;
use App\Notifications\AppointmentReminder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $appointments = Appointment::with(['booking', 'lawyer', 'client.user'])
            // ─── الفلاتر ───
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(fn ($sub) => $sub->where('notes', 'like', "%{$search}%")
                                            ->orWhereHas('lawyer', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                                            ->orWhereHas('client.user', fn ($u) => $u->where('name', 'like', "%{$search}%")));
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('lawyer'), fn ($q) => $q->where('users_id', $request->lawyer))
            ->when($request->filled('client'), fn ($q) => $q->where('clients_id', $request->client))
            ->when($request->filled('date_from'), fn ($q) => $q->whereDate('appointment_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($q) => $q->whereDate('appointment_date', '<=', $request->date_to))
            ->latest('appointment_date')
            ->paginate(15)
            ->withQueryString();

        // ─── بيانات الفلاتر ───
        $lawyers = User::where('user_type', 'lawyer')->orderBy('name')->get();
        $clients = Client::with('user')->orderBy('id')->get();

        return view('cms.appointments.index', compact('appointments', 'lawyers', 'clients'));
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
            'status'           => 'required|in:مجدول,مكتمل,ملغي',
            'bookings_id'      => 'nullable|exists:bookings,id',
            'users_id'         => 'required|exists:users,id',
            'clients_id'       => 'required|exists:clients,id',
        ]);

        $appointment = Appointment::create($data);

        if ($appointment->lawyer) {
            $appointment->lawyer->notify(new AppointmentReminder($appointment));
        }

        if ($appointment->client?->user) {
            $appointment->client->user->notify(new AppointmentReminder($appointment));
        }

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
            'status'           => 'required|in:مجدول,مكتمل,ملغي',
            'bookings_id'      => 'nullable|exists:bookings,id',
            'users_id'         => 'required|exists:users,id',
            'clients_id'       => 'required|exists:clients,id',
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