<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    public function index() {
        $user = Auth::user();
        return response()->json($user->categories);
    }

    public function create(Request $request) {
        $input = $request->validate([
            'name' => ['required', 'max:32']
        ], [
            'name.required' => "Enter Category Name",
            'name.max' => "Name must be less than 32 digits"
        ]);

        $input["name"] = strtolower($input["name"]);

        $category = Auth::user()->categories()->create($input);

        return response()->json($category);
    }

    public function update(Request $request, Category $category) {

        $inputs = $request->validate([
            "name" => ["required", "max:32"],
            "id" => ["numeric"]
        ], [
            'name.required' => "Enter Category Name",
            'name.max' => "Name must be less than 32 digits",
            'id.numeric' => ''
        ]);
        
        Gate::authorize('update', $category);

        $category->name = strtolower($inputs['name']);
        $category->save();
        
        return response()->json($category);
    }

    public function destroy(Category $category) {
    
        Gate::authorize('delete', $category);

        $category->delete();
        
        return response()->json("deleted");
    }
}
