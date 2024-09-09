<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    //

    public function index(Request $request)
    {
        $search = $request->input('search');

        $items = Score::with('score_category', 'score_participant', 'score_user')->when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.category', ['items' => $items, 'search' => $search]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
            'score' => 'required|string|max:255',
        ]);

        $existingScore = Score::where('participant_id', $request->input('participant_id'))
            ->where('category_id', $request->input('category_id'))
            ->where('user_id', $request->input('user_id'))
            ->first();

        if ($existingScore) {
            return response()->json(['message' => 'You have already rated this participant in this category.'], 409);
        }

        $score = new Score();
        $score->participant_id = $request->input('participant_id');
        $score->category_id = $request->input('category_id');
        $score->user_id = $request->input('user_id');
        $score->score = $request->input('score');

        $score->save();

        return response()->json(['message' => 'Rating successfully submitted.']);
    }
}
