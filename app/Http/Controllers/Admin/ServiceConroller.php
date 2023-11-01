<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;


class ServiceConroller extends Controller
{
    public function serviceAdd(Request $request){
        $categories = Category::where('parent_id',0)->get();
        $providers = \App\Models\Provider::get();
        $zones = Zone::get();
        return view('admin.page.service.add',compact('categories','providers','zones'));
    }

    public function Store(Request $request)
    {


        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'category_id' => 'required',
            'subcategory_id'=>'required',
            'provider_id' => 'required',
            'zone_id'=>'required',
            'price' => 'required|numeric',
            'price_type' => 'required',
            'duration' => 'required|string',
            'discount' => 'required|numeric',
            'image' => 'required',
            'status'=> 'required',
            'short_description' => 'required|string',
            'long_description' => 'required|string',
            'is_featured' => 'nullable',
        ]);


        $serviceExists = Service::where('name', $validatedData['name'])
            ->whereCategoryId('subcategory_id')
            ->where('provider_id', $request->provider)
            ->exists();



        if ($serviceExists) {
            Session::flash('toaster', ['status' => 'error', 'message' => 'Service already exists']);
            return back();
        }

        // Create a new service instance
        $service = new Service();
        $service->name = $validatedData['name'];
        $service->category_id = $validatedData['subcategory_id'];
        $service->provider_id = $validatedData['provider_id'];
        $service->zone_id = $validatedData['zone_id'];
        $service->price = $validatedData['price'];
        $service->type = $validatedData['price_type'];
        $service->duration = $validatedData['duration'];
        $service->discount = $validatedData['discount'];
        $service->status = $validatedData['status'];
        $service->short_description = $validatedData['short_description'];
        $service->long_description = $validatedData['long_description'];
        $service->is_featured = $request->has('is_featured') ? 1 : 0;
        if ($request->hasFile('image')) {
//            $imagePath = $request->file('image')->store('public/images');
//            // Save the image path in the database
//            $validatedData['image'] = Storage::url($imagePath);
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = 'public/images/' . $imageName;
            Image::make($image)->resize(200, 200)->save(storage_path('app/' . $imagePath));
            $service->image = $imageName;
        }
        $service->added_by = auth()->user()->id; // Assuming the authenticated user is adding the service

        // Save the service
        $service->save();

        // Optionally, you can redirect or return a response
        Session::flash('toaster', ['status' => 'success', 'message' => 'Service saved successfully']);
        return redirect()->route('list.service');
    }
    public function list(){
        $services = Service::with('category')->get();
        return view('admin.page.service.list',compact('services'));
    }

}
