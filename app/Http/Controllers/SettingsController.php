<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;

class SettingsController extends Controller
{
    //settings section
    public function SettingsSection()
    {
        return view('backend.pages.settings');
    }
    public function SettingsList()
    {
        $settings = Settings::first();

        return response()->json([
            'status' => 'success',
            'data' => $settings
        ]);
    }

    public function SettingsSave(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:50',
                'address' => 'nullable|string|max:255',
                'copyright' => 'nullable|string|max:255',
                'facebook' => 'nullable|url|max:255',
                'twitter' => 'nullable|url|max:255',
                'linkedin' => 'nullable|url|max:255',
                'github' => 'nullable|url|max:255',
                'youtube' => 'nullable|url|max:255',
                'whatsapp' => 'nullable|string|max:255',
                'header_logo' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:2048',
                'footer_logo' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:2048',
                'favicon' => 'nullable|file|mimes:ico,jpg,jpeg,png,webp,svg|max:1024',
            ]);

            $settings = Settings::first();

            if (!$settings) {
                $settings = new Settings();
            }

            $settings->email = $validated['email'] ?? null;
            $settings->phone = $validated['phone'] ?? null;
            $settings->address = $validated['address'] ?? null;
            $settings->copyright = $validated['copyright'] ?? null;

            $settings->facebook = $validated['facebook'] ?? null;
            $settings->twitter = $validated['twitter'] ?? null;
            $settings->linkedin = $validated['linkedin'] ?? null;
            $settings->github = $validated['github'] ?? null;
            $settings->youtube = $validated['youtube'] ?? null;
            $settings->whatsapp = $validated['whatsapp'] ?? null;

            $path = public_path('frontend/images/settings');

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            if ($request->hasFile('header_logo')) {
                if ($settings->header_logo && file_exists(public_path($settings->header_logo))) {
                    unlink(public_path($settings->header_logo));
                }

                $file = $request->file('header_logo');
                $filename = 'header_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $filename);
                $settings->header_logo = 'frontend/images/settings/' . $filename;
            }

            if ($request->hasFile('footer_logo')) {
                if ($settings->footer_logo && file_exists(public_path($settings->footer_logo))) {
                    unlink(public_path($settings->footer_logo));
                }

                $file = $request->file('footer_logo');
                $filename = 'footer_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $filename);
                $settings->footer_logo = 'frontend/images/settings/' . $filename;
            }

            if ($request->hasFile('favicon')) {
                if ($settings->favicon && file_exists(public_path($settings->favicon))) {
                    unlink(public_path($settings->favicon));
                }

                $file = $request->file('favicon');
                $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $filename);
                $settings->favicon = 'frontend/images/settings/' . $filename;
            }

            $settings->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Settings saved successfully'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
