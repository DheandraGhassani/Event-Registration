<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendances;
use App\Models\Event_Registration;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $registrations = Event_Registration::with(['user', 'subEvent.event'])
            ->where('payment_status', 'verified')
            ->whereDoesntHave('attendance')
            ->get();

        return view('committee.attendances.index', compact('registrations'));
    }

    public function scan(Request $request, $registrationId)
    {
        $request->validate([
            'certificate_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5MB
        ]);

        $path = $request->file('certificate_file')->store('certificates', 'public');

        Attendances::create([
            'registration_id' => $registrationId,
            'scan_time' => now(),
            'scanned_by' => Auth::id(),
            'uploaded_by' => Auth::id(),
            'uploaded_at' => now(),
            'certificate_file' => $path,
        ]);

        return back()->with('success', 'Presensi dan sertifikat berhasil dicatat.');
    }

    public function scannedList()
    {
        $registrations = Event_Registration::with(['user', 'subEvent.event', 'attendance'])
            ->where('payment_status', 'verified')
            ->whereHas('attendance') // hanya yang sudah melakukan presensi
            ->get();

        return view('committee.attendances.scanned', compact('registrations'));
    }
}
