<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\File;

class ProjectController extends Controller
{
    // project section
    public function ProjectSection(){
        return view('backend.pages.projectsection');    
    }
    // project list
    public function ProjectList()
    {
    $projects = Project::with('category')->latest()->get();

    return response()->json([
        'status' => 'success',
        'rows' => $projects
    ]);
    }
    // project create
    public function ProjectCreate(Request $request)
{
    $imagePath = null;

    if ($request->hasFile('image')) {

        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();

        // 👉 এখানে path change করা হয়েছে
        $destinationPath = public_path('frontend/images/project');

        // folder না থাকলে create করবে
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $image->move($destinationPath, $imageName);

        // 👉 database এ save path
        $imagePath = 'frontend/images/project/' . $imageName;
    }

    Project::create([
        'title' => $request->title,
        'description' => $request->description,
        'teach_stack' => json_encode($request->teach_stacks),
        'image' => $imagePath,
        'project_link' => $request->project_link,
        'github_link' => $request->github_link,
        'category_id' => $request->category_id,
        'status' => $request->status,
        'views' => $request->views ?? 0,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Project created successfully'
    ]);
}
// view count
public function ProjectViewCount($id)
{
    $project = Project::find($id);

    if (!$project) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Project not found'
        ], 404);
    }

    $project->increment('views');

    return response()->json([
        'status' => 'success',
        'message' => 'View count updated'
    ]);
}

// project by id
public function ProjectById(Request $request)
{
    $project = Project::with('category')->find($request->id);

    if (!$project) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Project not found'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'row' => $project
    ]);
}
// project update
public function ProjectUpdate(Request $request)
{
    $request->validate([
        'id' => 'required|exists:projects,id',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'teach_stacks' => 'required|array|min:1',
        'teach_stacks.*' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        'project_link' => 'nullable|url',
        'github_link' => 'nullable|url',
        'category_id' => 'nullable|exists:categories,id',
        'status' => 'required|in:live,dev,archived',
        'views' => 'nullable|integer'
    ]);

    $project = Project::find($request->id);

    if (!$project) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Project not found'
        ], 404);
    }

    $imagePath = $project->image;

    if ($request->hasFile('image')) {
        if ($project->image && File::exists(public_path($project->image))) {
            File::delete(public_path($project->image));
        }

        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();

        $destinationPath = public_path('frontend/images/project');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $image->move($destinationPath, $imageName);

        $imagePath = 'frontend/images/project/' . $imageName;
    }

    $project->update([
        'title' => $request->title,
        'description' => $request->description,
        'teach_stack' => json_encode($request->teach_stacks),
        'image' => $imagePath,
        'project_link' => $request->project_link,
        'github_link' => $request->github_link,
        'category_id' => $request->category_id,
        'status' => $request->status,
        'views' => $request->views ?? 0,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Project updated successfully'
    ]);
}
// project delete
public function ProjectDelete(Request $request)
    {
        try {
            // Find the advertisement by ID
            $project = Project::find($request->input('id'));
    
            if ($project) {
                // Get the image path from the advertisement record
                $imagePath = public_path($project->image);
    
                // Delete the image from the server if it exists
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
    
                // Delete the project record
                $project->delete();
    
                return response()->json([
                    'status' => 'success',
                    'message' => 'project deleted'
                ]);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'project not found'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }
}
