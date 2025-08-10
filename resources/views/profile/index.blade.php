@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>My Profile</h2>
                    <p class="mb-0">Welcome back, {{ $user->name }}!</p>
                </div>

                <div class="card-body">
                    <h3>My Posts</h3>
                    
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
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            You haven't created any posts yet. 
                            <a href="{{ route('posts.create') }}" class="alert-link">Create your first post</a>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
