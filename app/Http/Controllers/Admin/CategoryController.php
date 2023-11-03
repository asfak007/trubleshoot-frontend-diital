<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Zone;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function categoryAdd(Request $request){
        $zones = Zone::get(['name','id']);
        return view('admin.page.category.add',compact('zones'));
    }
    public function categorylist(Request $request)
    {
        $categories = DB::table('categories')
            ->select('categories.id', 'categories.name', 'categories.parent_id', 'categories.is_active', 'categories.is_featured', 'categories.zone_id', 'categories.image', DB::raw('(SELECT COUNT(*) FROM (SELECT DISTINCT SUBSTRING_INDEX(zone_id, ",", -1) AS single_zone FROM categories) AS c WHERE FIND_IN_SET(single_zone, categories.zone_id)) + 1 AS zones_count'))
            ->where('parent_id', 0)
            ->groupBy('categories.id', 'categories.name', 'categories.parent_id', 'categories.is_active', 'categories.is_featured', 'categories.zone_id', 'categories.image')
            ->get();

        $categories = $categories->map(function ($category) {
            $category->zone_id= explode(',', $category->zone_id);
            $category->zones_count = count($category->zone_id);
            $subcategoryCount = DB::table('categories')
                ->where('parent_id', $category->id)
                ->count();

            $category->subcategories_count = $subcategoryCount;
            return $category;
        });


         return view('admin.page.category.list', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            // Check if the category already exists in the provided zone
            $existingCategory = Category::where('name', $request->name)->where('zone_id', $request->zone)->first();

            if ($existingCategory) {
                Session::flash('toaster', ['status' => 'error', 'message' => 'Zone already exist in this category']);
                return redirect()->route('list.category');
            }


            $validatedData = $request->validate([
                'name' => 'required',
                'zone' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust the validation rules for the image if needed
            ]);
            // Create a new category
            $category = Category::create([
                'name' => $validatedData['name'],
                'zone_id' => $request->zone,  // directly assign the value from the request

                // Add other category properties as needed
            ]);

            // Upload the image file
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
            $message = 'Category added successfully';
            $status = 'success';

            return redirect()->route('list.category')
                ->with('toaster', ['status' => $status, 'message' => $message]);
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

        return redirect()->route('list.category');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id); // Use firstOrFail() to get a single category by its ID
        $zones = Zone::get(['name', 'id']);
        return view('admin.page.category.edit')->with(compact('zones', 'category'));
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
                return redirect()->route('edit.category', $id)
                    ->withErrors($validator)
                    ->withInput();
            }

            // Update the category name if provided
            if ($request->has('name')) {
                $category->name = $request->name;
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

            // Save the category
            $category->save();

            return redirect()->route('list.category')->with('toaster', [
                'status' => 'success',
                'message' => 'Category updated successfully!',
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the update process
            return redirect()->route('edit.category', $id)->with('toaster', [
                'status' => 'error',
                'message' => 'Failed to update category!',
            ]);
        }
    }

    public function getSubcategories($category)
    {
        $subcategories = Category::where('parent_id', $category)->where('parent_id', '!=', 0)->get();

        return response()->json($subcategories);
    }
    public function getCategories($zone)
    {
        $categories = Category::where('zone_id', $zone)->where('parent_id',  0)->get();

        return response()->json($categories);
    }
}
