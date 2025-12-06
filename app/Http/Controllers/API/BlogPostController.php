<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = BlogPost::get();
        return response()->json([
            'status' => 'Succes',
            'count' => count($posts),
            'data' => $posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required|numeric',
                'category_id' => 'required|numeric',
                'title' => 'required',
                'content' => 'required',
                'thumbnail' => 'nullable|image|max|2048',
                'meta_title' => 'required',
                'meta_description' => 'required',
                'meta_keywords' => 'required',



            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'errors' => $validator->errors()
            ], 400);
        }

        //check the user
        $loggedInUser = Auth::user();

        if ($loggedInUser->id != $request->use_id) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Unauthorized access'
            ], 403);
        }

        //checking category
        $category = BlogCategory::find($request->category_id);
        if (!$category) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Category not found'
            ], 404);
        }

        $imagePath = null;

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/posts'), $filename);
            $imagePath = 'storage/posts/' . $filename;
        }

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'excerpt' => $request->excerpt,
            'thumbnail' => $imagePath,
            'content' => $request->content,

        ];
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
