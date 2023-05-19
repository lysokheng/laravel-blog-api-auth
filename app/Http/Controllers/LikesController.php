<?php

namespace App\Http\Controllers;

use App\Models\Likes;
use App\Models\Post;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    // get linkes by post id
    public function getLikesByPostId($id)
    {
        $post = Post::find($id);
        $likes = $post->likes;
        return response()->json($likes);
    }

    // like post
    public function likePost(Request $request)
    {
        $user = auth()->user();
        $data = $request->all();

        $data['user_id'] = $user->id;
        // $data = $request->all();
        $like = Likes::create($data);
        return response()->json($like);
    }

    // unlike post
    public function unlikePost($id)
    {
        $like = Likes::find($id);
        $like->delete();
        return response()->json($like);
    }
}