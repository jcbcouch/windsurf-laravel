<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        // Create the post and associate it with the authenticated user
        $post = new Post($validated);
        $post->user_id = auth()->id();
        $post->save();

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Eager load comments with their authors and likes to prevent N+1 queries
        $post->load(['comments.user', 'likes']);
        
        // Append the likes_count and is_liked attributes
        $post->append(['likes_count', 'is_liked']);
        
        return view('posts.show', compact('post'));
    }

    /**
     * Store a newly created comment for the post.
     */
    public function storeComment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment = new Comment([
            'body' => $validated['body'],
            'user_id' => auth()->id(),
        ]);

        $post->comments()->save($comment);

        return back()->with('success', 'Comment added successfully');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroyComment(Post $post, Comment $comment)
    {
        if (Auth::id() !== $comment->user_id && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully');
    }

    /**
     * Like the specified post.
     */
    public function like(Post $post)
    {
        if (!auth()->check()) {
            return back()->with('error', 'You must be logged in to like a post.');
        }

        if (auth()->user()->hasLiked($post)) {
            return back();
        }

        $post->likes()->attach(auth()->id());

        return back();
    }

    /**
     * Unlike the specified post.
     */
    public function unlike(Post $post)
    {
        if (!auth()->check()) {
            return back()->with('error', 'You must be logged in to unlike a post.');
        }

        $post->likes()->detach(auth()->id());

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post->update($validated);

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully');
    }
}