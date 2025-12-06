<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = BlogCategory::all();
        return response()->json([
            'status' => 'Success',
            'count' => count($categories),
            'data' => $categories


        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'errors' => $validator->errors()
            ]);
        }

        $data['name'] = $request->name;
        $data['slug'] = Str::slug($request->slug);

        BlogCategory::create($data);

        return response()->json(
            [
                'status' => 'Success',
                'message' => 'Blog category created successfully'
            ]
        );
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
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'errors' => $validator->errors()
            ]);
        }

        $category = BlogCategory::find($id);
        if ($category) {
            $category['name'] = $request->name;
            $category['slug'] = $request->slug;
            $category->save();

            return response()->json([
                'status' => 'Success',
                'message' => 'Category updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Category not found'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
