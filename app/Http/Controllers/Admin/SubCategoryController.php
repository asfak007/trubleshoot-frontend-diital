<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class SubCategoryController extends Controller
{
    public function subcategoryAdd(Request $request){
        $categories = Category::where('parent_id', 0)->get();
        return view('admin.page.subcategory.add',compact('categories'));
    }
    public function subcategorylist(Request $request){
        $categories = Category::join('categories AS parent', 'categories.parent_id', '=', 'parent.id')
            ->where('categories.parent_id', '!=', 0)
            ->select('categories.id', 'categories.name', 'categories.is_active', 'categories.is_featured','categories.image',  'parent.name AS parent_name')
            ->get();
        return view('admin.page.subcategory.list',compact('categories'));
    }
    public function subCategoryStore(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required',
                'category_id' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust the validation rules for the image if needed

            ]);



            $existingCategory = Category::where('name', $request->name) ->where('parent_id', '!=', 0)->count();
            if ($existingCategory) {

                return redirect()->back()
                    ->with('toaster', ['status' => 'error', 'message' => 'Category name already exists.']);
            }

            // Create a new category
            $category = new Category();
            $category->name = $request->input('name');
            $category->parent_id = $request->input('category_id');


            // Add more category properties as needed
            $category->save();
            if ($request->hasFile('image')) {

                $image = $request->file('image');
                $destinationPath = public_path('assets/images/category/');
                $postImage = "category_$category->id.png";
                $fullPath = $destinationPath . $postImage;

                // Check if the directory exists, if not create it
                if (!file_exists($destinationPath)) {
                    if (!mkdir($destinationPath, 0777, true) && !is_dir($destinationPath)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $destinationPath));
                    }
                }

                // Save the image to the specified path
                Image::make($image)->resize(200, 200)->save($fullPath);
                $category->image = '/assets/images/category/'.$postImage;
                $category->save();
            }

            // Redirect to a success page or return a response
            return redirect()->route('list.subcategory')
                ->with('toaster', ['status' => 'success', 'message' => 'Category added successfully']);

        }catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('toaster', [
                    'status' => 'error',
                    'message' => 'There were some validation errors.',
                    'errors' => $e->errors()
                ]);
        }

        // Validate the form data
    }

    public function edit($id){
        $category = Category::findOrFail($id); // Use firstOrFail() to get a single category by its ID
        $categories = Category::where('parent_id',0)->get();

        return view('admin.page.subcategory.edit',compact('categories','category'));
    }
    public function update(Request $request, $id)
    {
        try {

            // Find the category by ID
            $category = Category::findOrFail($id);

            // Validate the form data
            $validator = Validator::make($request->all(), [
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust the validation rules for the image if needed
            ]);

            if ($validator->fails()) {
                return redirect()->route('subcategory.edit', $id)
                    ->with('toaster', [
                        'status' => 'error',
                        'message' => 'Image must be in jpeg,png,jpg,gif,svg',
                    ]);
            }

            // Update the category name if provided
            if ($request->has('name')) {
                $category->name = $request->name;
            }
            if ($request->has('name')) {
                $category->parent_id = $request->category_id;
            }

            // Upload and update the image file if provided
            if ($request->hasFile('image')) {
                // Delete the existing image if it exists
                if ($category->image) {
                    $path = public_path( $category->image);
                    if (file_exists($path)) {
                        unlink($path);
                    }

                }

                $image = $request->file('image');
                $destinationPath = public_path('assets/images/category/');
                $postImage ="category_$category->id.png";
                $fullPath = $destinationPath . $postImage;

                // Check if the directory exists, if not create it
                if (!file_exists($destinationPath)) {
                    if (!mkdir($destinationPath, 0777, true) && !is_dir($destinationPath)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $destinationPath));
                    }
                }

                // Save the image to the specified path
                Image::make($image)->resize(200, 200)->save($fullPath);
                $category->image = '/assets/images/category/'.$postImage;
                $category->save();
            }

            // Save the category
            $category->save();

            return redirect()->route('list.subcategory')->with('toaster', [
                'status' => 'success',
                'message' => 'Sub Category updated successfully!',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('toaster', [
                    'status' => 'error',
                    'message' => 'Failed to update Sub Category.',
                    'errors' => $e->errors()
                ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            // Find the category by ID
            $category = Category::findOrFail($id);

            if ($category->image) {
                $path = public_path( $category->image);
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // Delete the category and its subcategories (if any)
            DB::transaction(function () use ($category) {
                $category->delete();
            });

            Session::flash('toaster', ['status' => 'success', 'message' => 'Category deleted successfully!']);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the deletion process
            Session::flash('toaster', ['status' => 'error', 'message' => 'Failed to delete category!']);
        }

        return redirect()->route('list.subcategory');
    }
}
