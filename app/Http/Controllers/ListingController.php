<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ListingController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the user's property listings.
     * The Livewire component handles data loading and interactions.
     */
    public function index()
    {
        return view('listings.index');
    }

    /**
     * Show the form for creating a new listing.
     * The Livewire component handles form submission.
     */
    public function create()
    {
        return view('listings.create');
    }

    /**
     * Display the specified listing with its photos.
     */
    public function show(Listing $listing)
    {
        $this->authorize('view', $listing);

        $listing->load('photos');

        return view('listings.show', [
            'listing' => $listing,
        ]);
    }

    /**
     * Show the form for editing the specified listing.
     * The Livewire component handles form submission and updates.
     */
    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);

        return view('listings.edit', [
            'listing' => $listing,
        ]);
    }

    /**
     * Delete the specified listing.
     */
    public function destroy(Listing $listing)
    {
        $this->authorize('delete', $listing);

        // Unassign from QR slot if assigned
        $listing->unassignFromQrSlot();

        // Delete photos from storage
        foreach ($listing->photos as $photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->file_path);
            \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->thumbnail_path);
        }
        $listing->photos()->delete();

        $listing->delete();

        return redirect()->route('listings.index')->with('message', 'Listing deleted successfully.');
    }
}
