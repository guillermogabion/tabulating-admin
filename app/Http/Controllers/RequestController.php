<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{
    //

    public function index(Request $request)
    {

        $search = $request->input('search');
        $table_header = [
            'Full Name',
            'Request for',
            'Action'
        ];

        $items = Request::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('requestfor', 'like', '%' . $search . '%');
        })->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('pages.requests', ['headers' => $table_header, 'items' => $items, 'search' => $search]);
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'message' => 'required|string|max:255',
            'requestfor' => 'required|string|max:255', // Assuming 'requestfor' is also required
        ]);

        // Create a new instance of your model (replace RequestModel with your actual model name)
        $req = new Request();

        // Assign values to model attributes
        $req->userId = auth()->user()->id; // Assigning the authenticated user's ID to 'userId'
        $req->message = $request->input('message'); // Assigning 'message' from request input
        $req->requestfor = $request->input('requestfor'); // Assigning 'requestfor' from request input

        // Save the instance to the database
        $req->save();

        // Optionally, redirect back with a success message
        return redirect()->back()->with('success', 'Request submitted successfully.');
    }
}
