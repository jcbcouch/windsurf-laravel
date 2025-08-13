@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">{{ $user->name }}'s Profile</h2>
                            <p class="mb-0">Member since {{ $user->created_at->format('F Y') }}</p>
                        </div>
                        @auth
                            @if(Auth::id() === $user->id)
                                <div class="text-end">
                                    <a href="{{ route('profile.picture.edit') }}" class="btn btn-sm btn-outline-primary">
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
                                     class="img-thumbnail rounded-circle" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mb-3" 
                                     style="width: 150px; height: 150px; margin: 0 auto;">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
