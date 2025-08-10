@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>All Posts</span>
                    <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">Create New Post</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($posts->count() > 0)
                        <div class="list-group">
                            @foreach($posts as $post)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">{{ $post->title }}</h5>
                                            <small>Posted {{ $post->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <p>No posts found. Create your first post!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
