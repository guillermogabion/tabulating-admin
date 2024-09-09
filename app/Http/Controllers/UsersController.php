<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'User ID',
            'Full Name',
            'Email',
            'Role',
            'Action'
        ];
        $items = User::when($search, function ($query, $search) {
            return $query->where('firstName', 'like', '%' . $search . '%')
                ->orWhere('lastName', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        })
            // ->where('role', 'student')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.users', ['headers' => $table_header, 'items' => $items, 'search' => $search]);
    }

    public function getEntities()
    {
        $entity = User::where('role', 'NOT LIKE', 'student')->get();

        return response()->json(['entity' => $entity]);
    }

    public function storeId(Request $request)
    {
        $request->validate([
            'userId' => 'required|string|max:255',
        ]);
        $user = new User();
        $user->userId = $request->input('userId');
        $user->save();

        return redirect()->route('users');
    }

    public function store(Request $request)
    {
        $request->validate([
            'userId' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role' => 'required|string',
        ]);

        $user = new User();
        $user->userId = $request->input('userId');
        $user->email = $request->input('email');
        $user->role = $request->input('role');
        $user->password = Hash::make('Password');

        $user->save();

        return redirect()->route('users');
    }

    public function update(Request $request)
    {
        $request->validate([
            'userId' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->id,
            'role' => 'required|string',
        ]);

        // Find the user by ID
        $item = User::findOrFail($request->id);

        // Update the user's fields
        $item->fill($request->all());

        // Save the updated user
        $item->save();

        return redirect()->route('users')->with('success', 'User updated successfully');
    }
    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:new,old',
        ]);

        $user = User::findOrFail($request->id);
        $user->status = $request->input('status');
        $user->save();

        return redirect()->route('users')->with('success', 'User status updated successfully');
    }


    public function self()
    {
        $user = User::find(auth()->user()->id);
        $token = $user->createToken('authToken')->accessToken;
        return response(['user' => $user, 'access_token' => $token]);
    }

    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'userId' => 'required',
            'password' => 'required',
        ]);

        // Attempt authentication
        if (!Auth::attempt(['userId' => $request->userId, 'password' => $request->password])) {
            return response(['message' => 'Login credentials are incorrect'], 401);
        }

        // Find the authenticated user and eager load the 'details' relationship
        $user = User::where('userId', $request->userId)->first();

        // Generate the access token
        $token = $user->createToken('authToken')->accessToken;

        // Return the response with user details and token
        return response(['user' => $user, 'access_token' => $token], 200);
    }


    public function logout(Request $request)
    {
        $request->user()->tokem()->delete();
    }
    public function indexMobile()
    {
        return User::get();
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Ensure $user is an instance of User
        if (!($user instanceof \App\Models\User)) {
            return response()->json(['error' => 'User instance is incorrect'], 500);
        }

        $user->password = Hash::make($request->input('password'));
        $user->status = 'old';
        $user->save(); // Save method should be available



        return response()->json(['message' => 'Password updated successfully.']);
    }
}
