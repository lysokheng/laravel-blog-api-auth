<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return response(['posts' => PostResource::collection($posts), 'meesage' => 'Successful'], 200);
    }
    public function store(Request $request)
    {
        $data = $request->all();
        // upload image to storage
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $image_name);
            $data['image'] = $image_name;
        }
        $post = Post::all($data);
        return response(['posts' => new PostResource($post), 'meesage' => 'Successful'], 200);
    }

    public function show(Post $post)
    {
        return response(['post' => new PostResource($post), 'meesage' => 'Successful'], 200);
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $image_name);
            $data['image'] = $image_name;

            // remove old image
            $old_image = public_path('images/' . $post->image);
            if (file_exists($old_image)) {
                @unlink($old_image);
            }
        }
        $post->update($data);
        return response(['user' => $post, 'message' => 'Success'], 200);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return response(['message' => 'Success']);
    }
}