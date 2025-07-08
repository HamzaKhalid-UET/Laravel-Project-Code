<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Comment;
use App\Models\user;

class CommentController extends Controller
{
    public function storeComment(Request $request)
    {

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'comment' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $comment = Comment::create($request->all());
        return response()->json($comment);
    }
    public function getuserComments($id)
    {

        $user = User::find($id)->comments;
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        // foreach ($user as $comment) {
        //     $comment->user->attributesToArray();
        // }

        foreach ($user as $userComment) {
            $comments[] = $userComment->comment;
        }
        if ($user) {
            return response()->json(['comments' => $comments], 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    
}
