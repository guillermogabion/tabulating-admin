<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use Illuminate\Http\Request;

class RequestModelController extends Controller
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

        $items = RequestModel::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('requestfor', 'like', '%' . $search . '%');
        })->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('pages.requests', ['headers' => $table_header, 'items' => $items, 'search' => $search]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
            'requested_id' => 'required|string|max:255',
            'user_id' => 'required|integer', // Add validation for user_id
            'subject' => 'required|string|max:255', // Add validation for subject
        ]);

        $req = new RequestModel();

        $req->user_id = $request->input('user_id'); // Use user_id from request
        $req->message = $request->input('message');
        $req->requested_id = $request->input('requested_id');
        $req->subject = $request->input('subject'); // Use subject from request

        $req->save();

        return response()->json(['message' => 'Request Submitted.']);
    }

    public function delete(Request $request)
    {
        RequestModel::findOrFail($request->id)->delete();
        return response()->json(['message' => 'Request Deleted.']);
    }


    public function update(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
            'userId' => 'required',
        ]);

        $item = RequestModel::findOrFail($request->id)->where('status', 0);
        $item->fill($request->all());
        $item->save();
        return response()->json(['message' => 'Request Updated.']);
    }

    public function updateStatusWeb(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:new,old',
        ]);

        $user = RequestModel::findOrFail($request->id);
        $user->status = $request->input('status');
        $user->save();

        return redirect()->route('users')->with('success', 'User status updated successfully');
    }

    public function updateStatusApi(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:new,old',
        ]);

        $user = RequestModel::findOrFail($request->id);
        $user->status = $request->input('status');
        $user->save();

        return response()->json(['message' => 'Request Status Updated.']);
    }

    public function getMyRequest()
    {
        $request = RequestModel::where('user_id', auth()->user()->id)->with('requestTo')->orderBy('created_at', 'desc')->get();
        return response()->json(['requests' => $request]);
    }
    public function getToMeRequest()
    {
        $request = RequestModel::where('requested_id', auth()->user()->id)->with('user')->orderBy('created_at', 'desc')->get();
        return response()->json(['requests' => $request]);
    }

    public function changeStatus(Request $request, $id)
    {
        // Validate the status field
        $validated = $request->validate([
            'status' => 'required|integer|in:0,1,2',
        ]);

        // Find the request by ID
        $requestModel = RequestModel::find($id);

        if (!$requestModel) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        // Update the status
        $requestModel->status = $validated['status'];
        $requestModel->save();

        return response()->json(['message' => 'Status updated successfully']);
    }
}
