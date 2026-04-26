<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;


class ContactController extends Controller
{
    //contact section
    public function ContactSection()
    {
        return view('backend.pages.contact');
    }
     public function ContactList()
    {
        $rows = Contact::latest()->get();

        return response()->json([
            'status' => 'success',
            'rows' => $rows
        ]);
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email',
        'subject' => 'nullable|string|max:255',
        'message' => 'required|string|max:5000',
    ]);

    Contact::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'subject' => $validated['subject'] ?? null,
        'message' => $validated['message'],
        'status' => 'new',
    ]);

    if ($request->expectsJson()) {
        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully!',
        ]);
    }

    return back()->with('success', 'Message sent successfully!');
}
 public function ContactById(Request $request)
{
    $request->validate([
        'id' => 'required|integer',
    ]);

    $row = Contact::find($request->id);

    if (!$row) {
        return response()->json([
            'status' => 'error',
            'message' => 'Contact not found'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'row' => $row
    ]);
}

  public function ContactUpdate(Request $request)
{
    try {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $contact = Contact::find($request->id);

        if (!$contact) {
            return response()->json([
                'status' => 'error',
                'message' => 'Contact not found'
            ], 404);
        }

        // 👉 ONLY STATUS UPDATE
        if ($request->has('status')) {
            $request->validate([
                'status' => 'in:new,read,replied'
            ]);

            $contact->status = $request->status;
            $contact->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated'
            ]);
        }

        // 👉 (optional) full update future use
        $contact->name = $request->name ?? $contact->name;
        $contact->email = $request->email ?? $contact->email;
        $contact->subject = $request->subject ?? $contact->subject;
        $contact->message = $request->message ?? $contact->message;

        $contact->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Contact updated'
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}
public function ContactDelete(Request $request)
{
    try {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $contact = Contact::find($request->id);

        if (!$contact) {
            return response()->json([
                'status' => 'error',
                'message' => 'Contact not found'
            ], 404);
        }

        $contact->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Contact deleted successfully'
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}
}
