<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // server side pagination
        $count =  $request->query("count");

        $posts = Post::latest()->simplePaginate($count);
        return response($posts);
    }

    public function store(Request $request)
    {
        $newPost = $request->validate([
            "title" => "required",
            "author_name" => "required",
            "content" => "required",
        ]);


        try {
            Post::create($newPost);
            return response('post created successfully', 200);
        } catch (\Exception $e) {
            return response("failed creating post: $e", 500);
        }
    }

    public function show(Post $post)
    {
        return response($post);
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            "title" => "required",
            "author_name" => "required",
            "content" => "required",
        ]);

        $post->author_name = $request->author_name;
        $post->content = $request->content;

        $oldPost = Post::find($post->id);

        if ($oldPost == $post) {
            return response("no changes", 200);
        }

        try {
            $post->save();
            return response("post updated successfully", 200);
        } catch (\Exception $e) {
            return response("failed updating post: $e", 500);
        }
    }

    public function destroy(Post $post)
    {

        try {
            $post->delete();
            return response("post deleted successfully", 200);
        } catch (\Exception $e) {
            return response("failed deleting post: $e", 500);
        }
    }
}
