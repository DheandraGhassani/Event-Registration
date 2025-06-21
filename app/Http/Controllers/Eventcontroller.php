<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class Eventcontroller extends Controller
{
    public function index()
    {
        
        $events = Event::all();
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
        ]);

        $event = new Event();
        $event->name = $request->name;
        $event->description = $request->description;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->location = $request->location;
        $event->created_by = Auth::id();
        $event->is_active = 1;

        /*if ($request->hasFile('poster')) {
            $event->poster = $request->file('poster')->store('posters', 'public');
        }*/

        $event->save();

        return redirect()->route('committee.committeedashboard')->with('success', 'Event berhasil dibuat.');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('committee.events.edit', compact('event'));
    }

    // Simpan perubahan data event
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
        ]);

        $event = Event::findOrFail($id);
        $event->name = $request->name;
        $event->description = $request->description;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->location = $request->location;

        $event->save();

        return redirect()->route('committee.committeedashboard')->with('success', 'Event berhasil diperbarui.');
    }

    // Hapus event
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Hapus poster jika ada
        /*if ($event->poster) {
            Storage::disk('public')->delete($event->poster);
        }*/

        $event->delete();

        return redirect()->route('committee.committeedashboard')->with('success', 'Event berhasil dihapus.');
    }
}
