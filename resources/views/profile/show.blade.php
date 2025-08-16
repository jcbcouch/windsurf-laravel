@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: {{ $user->background_color }}55;"> 
                <div class="card-header" style="background-color: {{ $user->background_color }};">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">{{ $user->name }}'s Profile</h2>
                            <p class="mb-0">Member since {{ $user->created_at->format('F Y') }}</p>
                        </div>
                        @auth
                            @if(Auth::id() === $user->id)
                                <div class="text-end">
                                    <a href="{{ route('profile.background.edit') }}" class="btn btn-sm btn-outline-light me-2">
                                        <i class="fas fa-palette me-1"></i> Change Background
                                    </a>
                                    <a href="{{ route('profile.picture.edit') }}" class="btn btn-sm btn-outline-light">
                                        <i class="fas fa-camera me-1"></i> Update Photo
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            @if($user->avatar)
                                <img src="{{ $user->avatar_url }}" alt="Profile Picture" 
                                     class="img-thumbnail rounded-circle border-3" 
                                     style="width: 150px; height: 150px; object-fit: cover; border-color: {{ $user->background_color }} !important;">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" 
                                     style="width: 150px; height: 150px; margin: 0 auto; background-color: {{ $user->background_color }};">
                                    <span class="display-4 text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <h3>{{ $user->name }}'s Posts</h3>
                    
                    @if($posts->count() > 0)
                        <div class="list-group">
                            @foreach($posts as $post)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">
                                            <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                                        </h5>
                                        <small>Posted {{ $post->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ Str::limit($post->body, 150) }}</p>
                                    @auth
                                        @if(Auth::id() === $user->id)
                                            <div class="mt-2">
                                                <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Are you sure you want to delete this post?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            {{ $user->name }} hasn't created any posts yet.
                        </div>
                    @endif

                    <div class="mt-5">
                        <h3>{{ $user->name }}'s Videos</h3>
                        
                        @if($user->videos->count() > 0)
                            <div class="list-group">
                                @foreach($user->videos as $video)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('videos.show', $video) }}" class="text-decoration-none">
                                                <h5 class="mb-1">Video #{{ $video->id }}</h5>
                                            </a>
                                            @auth
                                                @if(Auth::id() === $user->id)
                                                    <form action="{{ route('videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                        @if($video->created_at)
                                            <small class="text-muted">
                                                Uploaded {{ $video->created_at->diffForHumans() }}
                                            </small>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No videos uploaded yet.</p>
                            @auth
                                @if(Auth::id() === $user->id)
                                    <a href="{{ route('videos.create') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-upload me-1"></i> Upload Your First Video
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
