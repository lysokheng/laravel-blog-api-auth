<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $comment = Comment::create($data);
        return response()->json($comment);
    }
    public function destroy($id)
    {
        // delete comment by commenter and post owner
        $comment = Comment::find($id);

        // get current user
        if ($comment) {
            $user = auth()->user();
            if ($user->id == $comment->user_id || $user->id == $comment->post->user_id) {
                $comment->delete();
                return response()->json($comment);
            } else {
                return response()->json(['message' => 'You are not authorized to delete this comment']);
            }
        } else {
            return response()->json(['message' => 'comment not found']);

        }
    }

    /// list comments by post id
    public function getCommentsByPostId($id)
    {
        $comments = Comment::where('post_id', $id)->with('user')->get();
        return response()->json($comments);
    }
}