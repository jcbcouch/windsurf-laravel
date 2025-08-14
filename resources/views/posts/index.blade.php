@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>All Posts</span>
                    <div class="d-flex align-items-center">
                        <span class="me-2">Sort by:</span>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $sort === 'most_liked' ? 'Most Liked' : 'Newest' }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                                <li><a class="dropdown-item {{ $sort === 'newest' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Newest</a></li>
                                <li><a class="dropdown-item {{ $sort === 'most_liked' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'most_liked']) }}">Most Liked</a></li>
                            </ul>
                        </div>
                        @auth
                            <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm ms-3">Create New Post</a>
                        @endauth
                    </div>
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
                                                @if($sort === 'most_liked')
                                                    • {{ $post->likes_count }} {{ Str::plural('like', $post->likes_count) }}
                                                @endif
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
