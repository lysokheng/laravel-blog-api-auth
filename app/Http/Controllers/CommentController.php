<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $comment = Comment::create($data);
        return response()->json($comment);
    }
    public function destroy($id)
    {
        // delete comment by comenter and post owner
        $comment = Comment::find($id);

        // get current user
        $user = auth()->user();
        if ($user->id == $comment->user_id || $user->id == $comment->post->user_id) {
            $comment->delete();
            return response()->json($comment);
        } else {
            return response()->json(['message' => 'You are not authorized to delete this comment']);
        }
    }
}