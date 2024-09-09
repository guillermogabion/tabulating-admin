<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Participants; // Assuming you have a Participant model
use App\Models\Category; // Assuming you have a Category model
use App\Models\Events;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        $category = Category::all();
        $event = Events::where('status', 'active')->get();

        $user = User::count();
        $participant = Participants::count();
        $event_total = Events::count();
        $results = Result::with(['result_participant', 'category_result'])
            ->where('category_id', $request->input('category_id'))
            ->get();

        // Initialize arrays to hold chart data
        $chartData = [
            'labels' => [],
            'data' => [],
        ];

        // Iterate through results and prepare data for the chart
        foreach ($results as $result) {
            $participantName = $result->result_participant ? $result->result_participant->name : 'Unknown';
            $categoryName = $result->category_result ? $result->category_result->name : 'Overall';
            $chartData['labels'][] = "{$participantName} - {$categoryName}";
            $chartData['data'][] = $result->result;
        }

        // Remove duplicates from labels if needed
        $chartData['labels'] = array_unique($chartData['labels']);

        // Pass the chart data to the view
        return view('pages.home', [
            'results' => $chartData,
            'categories' => $category,
            'events' => $event,
            'total_user' => $user,
            'total_event' => $event_total,
            'total_participant' => $participant,
        ]);
    }


    public function fetchCategoryData(Request $request)
    {
        // Get the category and event IDs from the request
        $categoryId = $request->input('category_id');
        $eventId = $request->input('event_id');

        // Fetch all categories (if needed for dropdown or other purposes)
        $categories = Category::all();

        // Build query to fetch results with related models
        $query = Result::with(['result_participant', 'category_result']);

        // Add category_id condition if provided
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Add event_id condition if provided
        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        // Execute query and get results
        $results = $query->get();

        // Initialize arrays to hold chart data
        $chartData = [
            'labels' => [],
            'data' => [],
        ];

        // Iterate through results and prepare data for the chart
        foreach ($results as $result) {
            $participantName = $result->result_participant ? $result->result_participant->name : 'Unknown';
            $categoryName = $result->category_result ? $result->category_result->name : 'Overall';
            $chartData['labels'][] = "{$participantName} - {$categoryName}";
            $chartData['data'][] = $result->result;
        }

        // Remove duplicates from labels if needed
        $chartData['labels'] = array_unique($chartData['labels']);

        // Return JSON response
        return response()->json([
            'results' => $chartData,
            'categories' => $categories
        ]);
    }
}
