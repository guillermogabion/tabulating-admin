<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Events;
use App\Models\Participants;
use App\Models\Result;
use App\Models\Score;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    //

    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'Participant No.',
            'Contestant Name',
            'Event',
            'Category',
            'Result',
        ];

        $items = Result::with('result_participant')
            ->when($search, function ($query, $search) {
                $query->whereHas('result_participant', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('category_result', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('event_result', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereDoesntHave('category_result', function ($query) use ($search) {
                        if (strtolower($search) == 'overall result') {
                            return $query->whereDoesntHave('category_result');
                        }
                    });
            })
            ->orderBy('result', 'desc')
            ->paginate(10);

        // For overall, if you need pagination, use paginate() here.
        $overall = Result::with('result_participant')
            ->where('category_id', NULL)
            ->orderBy('result', 'desc')
            ->paginate(10); // Added pagination here

        $category = Category::all();
        $participant = Participants::all();
        $events = Events::where('status', 'active')->get();

        return view('pages.results', [
            'headers' => $table_header,
            'items' => $items,
            'categories' => $category,
            'participants' => $participant,
            'events' => $events,
            'search' => $search,
            'overall' => $overall
        ]);
    }


    public function calculate(Request $request)
    {
        $items = Score::where('participant_id', $request->input('participant_id'))
            ->where('category_id', $request->input('category_id'))
            ->sum('score');

        $existingResult = Result::where('participant_id', $request->input('participant_id'))
            ->where('category_id', $request->input('category_id'))
            ->where('event_id', $request->input('event_id'))
            ->first();

        if ($existingResult) {
            $existingResult->result = $items;
            $existingResult->save();

            return response()->json(['message' => 'Result updated successfully.']);
        } else {
            $result = new Result();
            $result->participant_id = $request->input('participant_id');
            $result->category_id = $request->input('category_id');
            $result->event_id = $request->input('event_id');
            $result->result = $items;
            $result->save();

            return response()->json(['message' => 'Calculation and result saved successfully.']);
        }
    }

    public function calculateAllCategories(Request $request)
    {
        $totalScore = Score::where('participant_id', $request->input('participant_id'))
            ->sum('score');
        $existingResult = Result::where('participant_id', $request->input('participant_id'))
            ->where('event_id', $request->input('event_id'))->where('category_id', null)
            ->first();

        if ($existingResult) {
            $existingResult->result = $totalScore;
            $existingResult->save();

            return response()->json(['message' => 'Total score across all categories updated successfully.']);
        } else {
            $result = new Result();
            $result->participant_id = $request->input('participant_id');
            $result->event_id = $request->input('event_id');
            $result->result = $totalScore;  // Save the total score across all categories
            $result->save();

            return response()->json(['message' => 'Total score across all categories saved successfully.']);
        }
    }
}
