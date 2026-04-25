<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Handnote;

class HandnoteController extends Controller
{
    // handnote section
    public function HandnoteSection()
    {
        return view('backend.pages.handnote');
    }

    public function HandnoteList()
    {
        $rows = Handnote::latest()->get();

        return response()->json([
            'status' => 'success',
            'rows' => $rows
        ]);
    }

    public function HandnoteCreate(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'target' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'target_date' => 'nullable|date',
            'status' => 'nullable|boolean',
        ]);

        Handnote::create([
            'title' => $request->title,
            'target' => $request->target,
            'note' => $request->note,
            'target_date' => $request->target_date,
            'status' => $request->boolean('status'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Hand note created successfully'
        ]);
    }

    public function HandnoteById(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:handnotes,id',
        ]);

        $row = Handnote::findOrFail($request->id);

        return response()->json([
            'status' => 'success',
            'row' => $row
        ]);
    }

    public function HandnoteUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:handnotes,id',
            'title' => 'required|string|max:255',
            'target' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'target_date' => 'nullable|date',
            'status' => 'nullable|boolean',
        ]);

        $handnote = Handnote::findOrFail($request->id);

        $handnote->update([
            'title' => $request->title,
            'target' => $request->target,
            'note' => $request->note,
            'target_date' => $request->target_date,
            'status' => $request->boolean('status'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Hand note updated successfully'
        ]);
    }

    public function HandnoteDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:handnotes,id',
        ]);

        $handnote = Handnote::findOrFail($request->id);
        $handnote->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Hand note deleted successfully'
        ]);
    }
}
