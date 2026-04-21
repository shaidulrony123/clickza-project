<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todolist;

class TodolistController extends Controller
{
    public function TodolistSection()
    {
        return view('backend.pages.todolist');
    }

    public function TodolistList()
    {
        $rows = Todolist::latest()->get();

        return response()->json([
            'status' => 'success',
            'rows' => $rows
        ]);
    }

    public function TodolistCreate(Request $request)
    {
        $request->validate([
            'task' => 'required|string|max:255',
            'description' => 'required|string',
            'is_completed' => 'nullable|boolean',
        ]);

        Todolist::create([
            'task' => $request->task,
            'description' => $request->description,
            'is_completed' => $request->is_completed ? 1 : 0,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'To do created successfully'
        ]);
    }

    public function TodolistById(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:todolists,id',
        ]);

        $row = Todolist::findOrFail($request->id);

        return response()->json([
            'status' => 'success',
            'row' => $row
        ]);
    }

    public function TodolistUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:todolists,id',
            'task' => 'required|string|max:255',
            'description' => 'required|string',
            'is_completed' => 'nullable|boolean',
        ]);

        $todo = Todolist::findOrFail($request->id);

        $todo->update([
            'task' => $request->task,
            'description' => $request->description,
            'is_completed' => $request->is_completed ? 1 : 0,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'To do updated successfully'
        ]);
    }

    public function TodolistDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:todolists,id',
        ]);

        $todo = Todolist::findOrFail($request->id);
        $todo->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'To do deleted successfully'
        ]);
    }
}
