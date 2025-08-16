<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreVideoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VideoController extends Controller
{
    /**
     * Show the form for uploading a new video.
     */
    public function create(): View
    {
        return view('videos.create');
    }

    /**
     * Store a newly uploaded video.
     */
    public function store(StoreVideoRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $videoFile = $request->file('video');
        
        // Store the video in the 'public/videos' directory
        $path = $videoFile->store('videos', 'public');
        
        // Create a new video record
        $video = new Video([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'path' => $path,
            'original_filename' => $videoFile->getClientOriginalName(),
            'mime_type' => $videoFile->getMimeType(),
            'size' => $videoFile->getSize(),
        ]);
        
        $video->save();
        
        return redirect()
            ->route('profile.index')
            ->with('status', 'video-uploaded');
    }

    /**
     * Display a listing of all videos.
     */
    public function index()
    {
        $videos = Video::with('user')->latest()->paginate(15);
        
        return view('videos.index', [
            'videos' => $videos
        ]);
    }

    /**
     * Display the specified video.
     */
    public function show(Video $video)
    {
        return view('videos.show', [
            'video' => $video->load('user') // Eager load the user relationship
        ]);
    }

    /**
     * Remove the specified video from storage.
     */
    public function destroy(Video $video): RedirectResponse
    {
        $this->authorize('delete', $video);
        
        // Delete the file from storage
        Storage::disk('public')->delete($video->path);
        
        // Delete the record from the database
        $video->delete();
        
        return back()->with('status', 'video-deleted');
    }
}
