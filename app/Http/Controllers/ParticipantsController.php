<?php

namespace App\Http\Controllers;

use App\Models\Participants;
use Illuminate\Http\Request;

class ParticipantsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'Participant No.',
            'Full Name',
            'Age',
            'Status',
            'Action'
        ];
        $items = Participants::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.participant', ['headers' => $table_header, 'items' => $items, 'search' => $search]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|string',
            'address' => 'required|string',
        ]);

        $user = new Participants();
        $user->name = $request->input('name');
        $user->age = $request->input('age');
        $user->address = $request->input('address');
        $user->other = $request->input('other');

        $user->save();

        return redirect()->route('participants');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|string',
            'address' => 'required|string',
        ]);

        // Find the user by ID
        $item = Participants::findOrFail($request->id);

        // Update the user's fields
        $item->fill($request->all());

        // Save the updated user
        $item->save();

        return redirect()->route('participants')->with('success', 'Participants updated successfully');
    }

    public function updateStatus(Request $request)
    {

        $user = Participants::findOrFail($request->id);
        $user->status = $request->input('status');
        $user->save();

        return redirect()->route('participants')->with('success', 'Participants status updated successfully');
    }

    public function delete(Request $request)
    {
        Participants::findOrFail($request->id)->delete();
        return response()->json(['message' => 'Participants Deleted.']);
    }

    public function getParticipants()
    {
        $items = Participants::where('status', 'active')->get();
        return response([
            'participants' => $items
        ]);
    }
}
