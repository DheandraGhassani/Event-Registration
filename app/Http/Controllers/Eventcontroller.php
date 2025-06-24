<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Sub_Events;
use Illuminate\Support\Facades\Auth;

class Eventcontroller extends Controller
{

    public function publicIndex()
    {
        $events = Event::with('subEvents')->get();
        return view('guest.guest', compact('events'));
    }

    public function memberIndex()
    {
        $events = Event::with('subEvents')->get();
        return view('member.memberdashboard', compact('events'));
    }


    public function index()
    {
        
        $events = Event::with('subEvents')->get();
        return view('committee.committeedashboard', compact('events'));
    }

    public function create()
    {
        return view('committee.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',

            // Validasi Sub Event
            'sub_name' => 'required|string|max:150',
            'sub_start_time' => 'required|date',
            'sub_end_time' => 'required|date|after_or_equal:sub_start_time',
            'sub_location' => 'required|string|max:255',
            'registration_fee' => 'required|numeric|min:0',
        ]);

        // Simpan Event Utama
        $event = new Event();
        $event->name = $request->name;
        $event->description = $request->description;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->location = $request->location;
        $event->created_by = Auth::id();
        $event->is_active = 1;
        $event->save();

        // Simpan Sub Event
        $sub = new Sub_Events();
        $sub->event_id = $event->id;
        $sub->name = $request->sub_name;
        $sub->start_time = $request->sub_start_time;
        $sub->end_time = $request->sub_end_time;
        $sub->speaker = $request->sub_speaker;
        $sub->location = $request->sub_location;
        $sub->registration_fee = $request->registration_fee;
        $sub->save();

        return redirect()->route('committee.committeedashboard')->with('success', 'Event dan Sub Event berhasil dibuat.');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('committee.events.edit', compact('event'));
    }

    // Simpan perubahan data event
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
        ]);

        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
        ]);

        if ($request->has('sub_events')) {
            foreach ($request->sub_events as $subEventData) {
                $sub = \App\Models\Sub_Events::find($subEventData['id']);
                if ($sub && $sub->event_id == $event->id) {
                    $sub->update([
                        'name' => $subEventData['name'],
                        'start_time' => $subEventData['start_time'],
                        'end_time' => $subEventData['end_time'],
                        'speaker' => $subEventData['speaker'],
                        'location' => $subEventData['location'],
                        'registration_fee' => $subEventData['registration_fee'],
                    ]);
                }
            }
        }

        return redirect()->route('committee.committeedashboard')->with('success', 'Event dan Sub Event berhasil diperbarui.');
    }


    // Hapus event
    public function destroy($id)
{
    // Cari event, jika tidak ditemukan akan 404
    $event = Event::findOrFail($id);

    // Hapus semua sub-event yang terkait
    $event->subEvents()->delete();

    // Hapus event itu sendiri
    $event->delete();

    // Redirect kembali ke dashboard dengan pesan
    return redirect()->route('committee.committeedashboard')
        ->with('success', 'Event dan semua sub-event berhasil dihapus.');
}

}
