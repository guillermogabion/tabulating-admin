<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //

    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'Category ID',
            'Title',
            'Event',
            'Status',
            'Action'
        ];
        $items = Category::with('event_category')->when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.category', ['headers' => $table_header, 'items' => $items, 'search' => $search]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required',
            'category.*.name' => 'required|string|max:255',
        ]);

        foreach ($request->input('category') as $categoryData) {
            $category = new Category();
            $category->event_id = $request->input('event_id');
            $category->name = $categoryData['name'];
            $category->save();
        }

        return redirect()->route('events');
    }

    public function update(Request $request)
    {


        $category = Category::findOrFail($request->id);

        $category->fill($request->all());

        $category->save();

        return redirect()->route('categories')->with('success', 'An event updated successfully');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:active,unavailable',
        ]);

        $user = Category::findOrFail($request->id);
        $user->status = $request->input('status');
        $user->save();

        return redirect()->route('categories')->with('success', 'Events status updated successfully');
    }

    public function delete($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getEventCategory($id)
    {
        $category = Category::where('event_id', $id)->get();

        return response()->json([
            'category' => $category,
        ]);
    }


    public function getAll()
    {
        $category = Category::all();

        return response()->json([
            'category' => $category,
        ]);
    }
}
