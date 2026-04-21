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
            $settings = Settings::first();

            if (!$settings) {
                $settings = new Settings();
            }

            $settings->email = $request->email;
            $settings->phone = $request->phone;
            $settings->address = $request->address;
            $settings->copyright = $request->copyright;

            $settings->facebook = $request->facebook;
            $settings->twitter = $request->twitter;
            $settings->linkedin = $request->linkedin;
            $settings->github = $request->github;
            $settings->youtube = $request->youtube;
            $settings->whatsapp = $request->whatsapp;

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
