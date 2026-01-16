<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        return Item::when($request->status, fn ($q) =>$q->where('status', $request->status))->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
        'name' => 'required',
        'code' => 'required|unique:items',
        'description' => 'nullable',
        'status' => 'required|in:active,inactive'
        ]);


        return Item::create($data);
    }


    public function show(string $id)
    {
        //
    }

    
    public function update(Request $request, Item $item)
    {
        $item->update($request->validate([
        'name' => 'required',
        'description' => 'nullable',
        'status' => 'required|in:active,inactive',
        'code' => 'required'
        ]));


        return $item;
    }
    
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json([
            'message' => 'Item deleted successfully'
        ], 200);
    }
}
