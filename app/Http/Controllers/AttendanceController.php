<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    //
    public function index(Request $request) {}

    public function scanCode(Request $request)
    {
        $exist = User::find($request('userId'));
        $existData = Attendance::find($request('userId'));

        if ($exist) {

            if ($existData) {
                $existData->update(['logout' => Carbon::now()]);
                return response()->json(['response' => $existData]);
            } else {
                $data = new Attendance();
                $data->user_id = $request('userId');
                $data->login = Carbon::now();
                $data->save();
                return response()->json(['response' => $data]);
            }
        }
    }
}
