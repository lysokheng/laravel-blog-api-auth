<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        /// get posts lasted first comment count and like count with pagination
        $posts = Post::withCount(['comments', 'likes'])->orderBy('created_at', 'desc')->paginate(20);
        return response(['posts' => $posts, 'message' => 'Successful'], 200);
    }
    public function store(Request $request)
    {
        /// get current user id
        $user = auth()->user();
        $data = $request->all();
        $data['user_id'] = $user->id;

        // upload image to storage
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('posts'), $image_name);
            $data['image'] = $image_name;
        }
        $post = Post::create($data);
        return response(['post' => new PostResource($post), 'message' => 'Successful'], 200);
    }

    public function show(Post $post)
    {
        return response(['post' => new PostResource($post), 'message' => 'Successful'], 200);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if ($post->user_id != auth()->user()->id) {
            return response(['message' => 'You are not authorized to update this post']);
        } else {
            $data = $request->all();
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('posts'), $image_name);
                $data['image'] = $image_name;

                // remove old image
                $old_image = public_path('posts/' . $post->image);
                if (file_exists($old_image)) {
                    @unlink($old_image);
                }
            }
            $post->update($data);
            return response(['user' => $post, 'message' => 'Success'], 200);
        }
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        /// delete post if user is owner
        if ($post->user_id != auth()->user()->id) {
            return response(['message' => 'You are not authorized to update this post']);
        } else {
            /// remove image
            $image = public_path('posts/' . $post->image);
            if (file_exists($image)) {
                @unlink($image);
            }
            $post->delete();
            return response(['message' => 'Success']);

        }
    }
}