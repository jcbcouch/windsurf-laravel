@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Post Details</span>
                    <div>
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <h3>{{ $post->title }}</h3>
                    <p class="text-muted">
                        Posted {{ $post->created_at->diffForHumans() }}
                        @if($post->created_at != $post->updated_at)
                            <br>Last updated {{ $post->updated_at->diffForHumans() }}
                        @endif
                    </p>
                    <hr>
                    <div class="post-content">
                        {!! nl2br(e($post->body)) !!}
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                            &larr; Back to Posts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
