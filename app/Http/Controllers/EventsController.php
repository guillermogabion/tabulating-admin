<?php

namespace App\Http\Controllers;

use App\Models\Events;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'Event ID',
            'Title',
            'Status',
            'Action'
        ];
        $items = Events::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.events', ['headers' => $table_header, 'items' => $items, 'search' => $search]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $event = new Events();
        $event->name = $request->input('name');

        $event->save();

        return redirect()->route('events');
    }

    public function update(Request $request)
    {


        $event = Events::findOrFail($request->id);

        $event->fill($request->all());

        $event->save();

        return redirect()->route('events')->with('success', 'An event updated successfully');
    }

    public function delete($id)
    {
        Events::findOrFail($id)->delete();
        return redirect()->route('pages.events')->with('success', 'An event deleted successfully');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:active,unavailable',
        ]);

        $user = Events::findOrFail($request->id);
        $user->status = $request->input('status');
        $user->save();

        return redirect()->route('events')->with('success', 'Events status updated successfully');
    }

    public function getEvents(Request $request)
    {
        $search = $request->input('search');

        $events = Events::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'events' => $events,
        ]);
    }

    public function addEvent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $event = new Events();
        $event->name = $request->input('name');

        $event->save();

        return response()->json([
            'events' => $event,
        ]);
    }
}
