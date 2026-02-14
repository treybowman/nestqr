<?php

namespace App\Http\Controllers;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     * The Livewire component handles profile updates, preferences, and branding.
     */
    public function index()
    {
        return view('settings.index');
    }
}
