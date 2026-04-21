<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    // about section
    public function AboutSection(){
        return view('backend.pages.aboutsection');
    }
    // about list
public function AboutList()
{
    $aboutInfo = About::first();

    return response()->json([
        'status' => 'success',
        'data'   => $aboutInfo
    ]);
}

public function AboutSave(Request $request)
{
    $request->validate([
        'title'       => 'required|string|max:255',
        'subtitle'    => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'tag'         => 'nullable|string',
        'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'downloadcv'          => 'nullable|mimes:pdf,doc,docx|max:10240',
    ]);

    $about = About::first() ?? new About();

    $about->title       = $request->title;
    $about->subtitle    = $request->subtitle;
    $about->description = $request->description;
    $about->tag         = $request->tag;   // comma separated tags

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('frontend/assets/images'), $imageName);
        $about->image = 'frontend/assets/images/' . $imageName;
    }

    if ($request->hasFile('downloadcv')) {
        $cv = $request->file('downloadcv');
        $cvName = time() . '_' . $cv->getClientOriginalName();
        $cv->move(public_path('frontend/assets/cv'), $cvName);
        $about->downloadcv = 'frontend/assets/cv/' . $cvName;
    }

    $about->save();

    return response()->json(['message' => 'About section saved successfully!']);
}
}
