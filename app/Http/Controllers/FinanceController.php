<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event_Registration;
use App\Models\Event;

class FinanceController extends Controller
{

    public function dashboard()
    {
        $events = Event::withCount('subEvents')->orderBy('start_date')->get();
        return view('finance.financedashboard', compact('events'));
    }

    public function viewRegistrations($eventId)
    {
        $event = Event::findOrFail($eventId);

        $registrations = Event_Registration::with(['user', 'subEvent'])
            ->whereHas('subEvent', function ($query) use ($eventId) {
                $query->where('event_id', $eventId);
            })
            ->get();

        return view('finance.registrations', compact('registrations', 'event'));
    }

    public function index()
    {
        $registrations = Event_Registration::with(['user', 'subEvent.event'])->get();
        return view('finance.registrations', compact('registrations'));
    }

    public function approve($id)
    {
        $registration = Event_Registration::findOrFail($id);
        $registration->payment_status = 'verified';
        $registration->save();

        return back()->with('success', 'Pembayaran berhasil disetujui.');
    }

    public function reject($id)
    {
        $registration = Event_Registration::findOrFail($id);
        $registration->payment_status = 'rejected';
        $registration->save();

        return back()->with('success', 'Pembayaran ditolak.');
    }
}
