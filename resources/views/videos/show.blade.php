@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Video Player -->
        <div class="aspect-w-16 aspect-h-9 bg-black">
            <video 
                class="w-full h-full object-contain" 
                controls
                controlsList="nodownload"
                poster="{{ $video->thumbnail_url ?? asset('images/video-thumbnail.jpg') }}"
            >
                <source src="{{ $video->getVideoUrl() }}" type="{{ $video->mime_type }}">
                Your browser does not support the video tag.
            </video>
        </div>
        
        <!-- Video Details -->
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $video->title }}</h1>
            
            <!-- Author and Upload Date -->
            <div class="flex items-center text-sm text-gray-500 mb-4">
                <span>Uploaded by {{ $video->user->name }}</span>
                <span class="mx-2">•</span>
                <span>{{ $video->created_at->diffForHumans() }}</span>
            </div>
            
            <!-- Description -->
            @if($video->description)
                <div class="prose max-w-none mb-6">
                    <p class="text-gray-700">{{ $video->description }}</p>
                </div>
            @endif
            
            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ url()->previous() }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Previous Page
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
