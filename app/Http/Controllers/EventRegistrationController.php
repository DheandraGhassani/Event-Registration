<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Sub_Events;
use App\Models\Event_Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class EventRegistrationController extends Controller
{
    // Tampilkan form pendaftaran
    public function showRegistrationForm($id)
    {
        $event = Event::findOrFail($id);
        return view('member.registerevent', compact('event'));
    }

    // Proses pendaftaran
    public function submitRegistration(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        $event = Event::findOrFail($id);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $subEvent = Sub_Events::where('event_id', $id)->firstOrFail();

        $qrCode = 'USER-' . Auth::id() . '-EVENT-' . $event->id . '-' . uniqid();

        Log::info('File path: ' . $path);
        Log::info('Sub Event ID: ' . $subEvent->id);
        
        $registration = Event_Registration::create([
            'user_id' => Auth::id(),
            'sub_event_id' => $subEvent->id,
            'payment_proof' => $path,
            'payment_status' => 'pending',
            'qr_code' => $qrCode,
        ]);

        Log::info('Registration ID: ' . $registration->id);

        return redirect()->route('member.registration.status', $registration->id)
                         ->with('success', 'Pendaftaran berhasil!');
    }

    // Tampilkan status
    public function showStatus($id)
    {
        $registration = Event_Registration::with(['subEvent'])->findOrFail($id);

        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }

        return view('member.registration_status', compact('registration'));
    }

    public function myRegistrations()
    {
        $registrations = Event_Registration::with(['subEvent', 'subEvent.event'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('member.my_registrations', compact('registrations'));
    }
}
