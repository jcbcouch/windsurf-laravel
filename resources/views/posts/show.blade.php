@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Post Details</span>
                    @auth
                        @if(Auth::id() === $post->user_id)
                        <div>
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                        @endif
                    @endauth
                </div>

                <div class="card-body">
                    <h3>{{ $post->title }}</h3>
                    <p class="text-muted">
                        Posted by 
                        <a href="{{ route('profile.show', $post->user) }}" class="text-decoration-none">
                            {{ $post->user->name }}
                        </a>
                        {{ $post->created_at->diffForHumans() }}
                        @if($post->created_at != $post->updated_at)
                            <br>Last updated {{ $post->updated_at->diffForHumans() }}
                        @endif
                    </p>
                    <hr>
                    <div class="post-content">
                        {!! nl2br(e($post->body)) !!}
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Comments ({{ $post->comments->count() }})</h5>
                </div>
                <div class="card-body">
                    @auth
                    <!-- Comment Form -->
                    <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="form-group">
                            <label for="body" class="form-label">Add a comment</label>
                            <textarea name="body" id="body" rows="3" class="form-control @error('body') is-invalid @enderror" required></textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">Post Comment</button>
                        </div>
                    </form>
                    @else
                        <div class="alert alert-info">
                            Please <a href="{{ route('login') }}">login</a> to leave a comment.
                        </div>
                    @endauth

                    <!-- Comments List -->
                    @forelse($post->comments as $comment)
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="rounded-circle" width="50" height="50">
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0">
                                        <a href="{{ route('profile.show', $comment->user) }}" class="text-decoration-none">
                                            {{ $comment->user->name }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">{{ $comment->body }}</p>
                                @auth
                                    @if(Auth::id() === $comment->user_id || Auth::user()->hasRole('admin'))
                                    <form action="{{ route('posts.comments.destroy', ['post' => $post, 'comment' => $comment]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0" onclick="return confirm('Are you sure you want to delete this comment?')">
                                            Delete
                                        </button>
                                    </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr class="my-3">
                        @endif
                    @empty
                        <p class="text-muted">No comments yet. Be the first to comment!</p>
                    @endforelse
                </div>
            </div>
            
            <div class="mb-4">
                <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                    &larr; Back to Posts
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
