<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use Illuminate\Http\Request;

class ParameterController extends Controller
{
    //

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'parameters.*.name' => 'required|string|max:255',
            'parameters.*.maximum' => 'required|string|max:255',
        ]);

        foreach ($request->input('parameters') as $parameterData) {
            $parameter = new Parameter();
            $parameter->category_id = $request->input('category_id');
            $parameter->name = $parameterData['name'];
            $parameter->maximum = $parameterData['maximum'];
            $parameter->save();
        }

        return redirect()->route('categories');
    }

    public function getCategoryParameter($id)
    {
        $parameter = Parameter::where('category_id', $id)->get();

        return response()->json([
            'parameter' => $parameter,
        ]);
    }
}
