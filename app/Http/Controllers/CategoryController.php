<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user_id = $request->header('id');
        $category = Category::create([
            'name' => $request->input('name'),
            'user_id' => $user_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }
    function showCategories(Request $request)
    {
        $user_id = $request->header('id');
        return Category::where('user_id', $user_id)->get();
    }

    function categoryEdit(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:categories,id',
        ]);

        $category_id = $request->input('id');
        $user_id = $request->header('id');
        $category = Category::where('id', $category_id)->where('user_id', $user_id)->first();

        if ($category) {
            return response()->json([
                'status' => 'success',
                'data' => $category
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found or not authorized'
            ], 404);
        }
    }

    function categoryUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:categories,id',
            'name' => 'required|string|max:255',
        ]);

        $category_id = $request->input('id');
        $user_id = $request->header('id');
        $updated = Category::where('id', $category_id)->where('user_id', $user_id)->update([
            'name' => $request->input('name'),
        ]);

        if ($updated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found or not authorized'
            ], 404);
        }
    }
    function categoryDestroy(Request $request)
    {
        $category_id = $request->input('id');
        $user_id = $request->header('id');
        $deleted = Category::where('id', $category_id)->where('user_id', $user_id)->delete();

        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found or not authorized'
            ], 404);
        }
    }
}
