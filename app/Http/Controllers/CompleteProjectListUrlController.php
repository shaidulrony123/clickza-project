<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompleteProjectListUrl;
use Illuminate\Support\Facades\File;

class CompleteProjectListUrlController extends Controller
{
    //complete-project-section 
    public function CompleteProjectSection()
    {
       return view('backend.pages.completeprojecturl');
    }
    // complete-project-list
     public function CompleteProjectList()
    {
        $rows = CompleteProjectListUrl::latest()->get();

        return response()->json([
            'status' => 'success',
            'rows' => $rows
        ]);
    }
    public function CompleteProjectCreate(Request $request)
{
    try {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'url' => 'required|url',
            'status' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('frontend/images/projectcompleteurllist'), $imageName);
        }

        CompleteProjectListUrl::create([
            'title' => $request->title,
            'url' => $request->url,
            'status' => $request->status == 'live' ? 1 : 0,
            'image' => $imageName,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Project created successfully'
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'fail',
            'message' => $e->getMessage()
        ], 500);
    }
}

public function CompleteProjectById(Request $request)
{
    $row = CompleteProjectListUrl::find($request->id);

    return response()->json([
        'status' => 'success',
        'row' => $row
    ]);
}

public function CompleteProjectUpdate(Request $request)
{
    try {
        $request->validate([
            'id' => 'required|exists:complete_project_list_urls,id',
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'status' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $project = CompleteProjectListUrl::findOrFail($request->id);

        $imageName = $project->image;

        if ($request->hasFile('image')) {
            if ($project->image && File::exists(public_path('frontend/images/projectcompleteurllist/' . $project->image))) {
                File::delete(public_path('frontend/images/projectcompleteurllist/' . $project->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('frontend/images/projectcompleteurllist'), $imageName);
        }

        $project->update([
            'title' => $request->title,
            'url' => $request->url,
            'status' => $request->status === 'live' ? 1 : 0,
            'image' => $imageName,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Project updated successfully'
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'fail',
            'message' => $e->getMessage()
        ], 500);
    }
}

public function CompleteProjectDelete(Request $request)
{
    try {
        $request->validate([
            'id' => 'required|exists:complete_project_list_urls,id',
        ]);

        $project = CompleteProjectListUrl::findOrFail($request->id);

        if ($project->image && File::exists(public_path('frontend/images/projectcompleteurllist/' . $project->image))) {
            File::delete(public_path('frontend/images/projectcompleteurllist/' . $project->image));
        }

        $project->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Project deleted successfully'
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'fail',
            'message' => $e->getMessage()
        ], 500);
    }
}
}
