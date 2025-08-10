@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>All Posts</span>
                    @auth
                        <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">Create New Post</a>
                    @endauth
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
                                <a href="{{ route('posts.show', $post) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">{{ $post->title }}</h5>
                                            <small class="text-muted">
                                                Posted by {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <span class="text-muted">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <p>No posts found. 
                            @auth
                                Create your first post!
                            @else
                                <a href="{{ route('login') }}">Log in</a> to create a post.
                            @endauth
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
