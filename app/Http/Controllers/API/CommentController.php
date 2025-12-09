<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::get();

        return response()->json([
            'status' => 'Succes',
            'count' => count($comments),
            'data' => $comments
        ], status: 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|integer|exists:blog_posts,id',
            'content' => 'required|string'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => $validator->errors()
            ], 400);
        }

        $data['post_id'] = $request->post_id;
        $data['user_id'] = auth()->id(); //showing error because of vscode but it will not be an error
        $data['content'] = $request->content;

        Comment::create($data);

        return response()->json([
            'status' => 'Succes',
            'message' => 'Comment added and waiting for approval'
        ]);
    }

    //for changing the comment status here

    public function changeStatus(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'comment_id' => 'required|exists:comments,id',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => $validator->errors()
            ], 400);
        }

        $comment = Comment::find($request->comment_id);
        $comment->status = $request->status;
        $comment->save();

        return response()->json([
            'status' => 'Succes',
            'message' => 'Comment status updated successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
