<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ExtraService;
use App\Models\Provider;
use App\Models\Service;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use PHPUnit\Exception;


class ServiceController extends Controller
{
    public function serviceAdd(){
        $categories = Category::where('parent_id',0)->get();
        $providers = \App\Models\Provider::get();
        $zones = Zone::get();
        return view('admin.page.service.add',compact('categories','providers','zones'));
    }
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'zone_id' => 'required|integer',
                'category_id' => 'required|integer',
                'subcategory_id' => 'nullable|integer',
                'provider_id' => 'required|integer',
                'price_type' => 'required|in:fixed,hourly',
                'price' => 'required|numeric',
                'discount' => 'required|numeric',
                'duration' => 'required',
                'status' => 'required|boolean',
                'short_description' => 'nullable|string',
                'long_description' => 'nullable|string',
                'image' => 'nullable|image',
                'is_featured' => 'required',
            ]);

            // Find the service by ID
            $service = Service::findOrFail($id);

            // Update the service with the request data
            $service->update([
                'name' => $request->name,
                'zone_id' => $request->zone_id,
                'category_id' => $request->subcategory_id,
                'provider_id' => $request->provider_id,
                'price_type' => $request->price_type,
                'price' => $request->price,
                'discount' => $request->discount,
                'duration' => $request->duration,
                'status' => $request->status,
                'short_description' => $request->short_description,
                'long_description' => $request->long_description,
                'is_featured' => $request->is_featured == 'on' ? 1 : 0,
            ]);

            // If there's a new image, handle the file upload here.
            if ($request->hasFile('image')) {

                $image = $request->file('image');
                $destinationPath = public_path('assets/images/service/');
                $postImage = "service_$service->id.png";
                $fullPath = $destinationPath . $postImage;

                // Check if the directory exists, if not create it
                if (!file_exists($destinationPath)) {
                    if (!mkdir($destinationPath, 0777, true) && !is_dir($destinationPath)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $destinationPath));
                    }
                }

                // Save the image to the specified path
                Image::make($image)->resize(200, 200)->save($fullPath);
                $service->image = '/assets/images/service/'.$postImage;
                $service->save();
            }
            Session::flash('toaster', ['status' => 'success', 'message' => 'Service update successfully.']);

            // Redirect back with a success message
            return redirect()->route('list.service');
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
        // Validate the request data

    }


    public function Store(Request $request)
    {

        try {
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
//                'status'=> 'required',
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
            $service->status = 1;
            $service->short_description = $validatedData['short_description'];
            $service->long_description = $validatedData['long_description'];
            $service->is_featured = $request->has('is_featured') ? 1 : 0;





            $service->by_admin = auth()->user()->id; // Assuming the authenticated user is adding the service

            // Save the service
            $service->save();
            if ($request->hasFile('image')) {

                $image = $request->file('image');
                $destinationPath = public_path('assets/images/service/');
                $postImage = "service_$service->id.png";
                $fullPath = $destinationPath . $postImage;

                // Check if the directory exists, if not create it
                if (!file_exists($destinationPath)) {
                    if (!mkdir($destinationPath, 0777, true) && !is_dir($destinationPath)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $destinationPath));
                    }
                }

                // Save the image to the specified path
                Image::make($image)->resize(200, 200)->save($fullPath);
                $service->image = '/assets/images/service/'.$postImage;
                $service->save();
            }

            // Optionally, you can redirect or return a response
            Session::flash('toaster', ['status' => 'success', 'message' => 'Service saved successfully']);
            return redirect()->route('list.service');
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


    }
    public function list(){
        $services = Service::with('category','provider')->get();

        return view('admin.page.service.list',compact('services'));
    }
    public function destroy( $id)
    {
        try {
            // Find the category by ID
            $service = Service::findOrFail($id);

            if ($service->image) {

                $path = public_path($service->image);
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // Delete the category and its subcategories (if any)
            DB::transaction(function () use ($service) {
                $service->delete();
                ExtraService::where('service_id', $service->id)->delete();

            });

            Session::flash('toaster', ['status' => 'success', 'message' => 'Service deleted successfully!']);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the deletion process
            Session::flash('toaster', ['status' => 'error', 'message' => 'Failed to delete Service!']);

        }

        return redirect()->route('list.service');
    }

    public function details($id){
        $service = Service::with(['provider','reviews.customer','extra_services'])->where('id',$id)->first();

        $extra_services = $service->extra_services;
        $reviews = $service->reviews;

        return view('admin.page.service.details', ['service' => $service, 'extra_services' => $extra_services,
            'reviews' => $reviews,]);
    }
    public function extraAdd(){
        $service = Service::where('status',1)->get();
        return view('admin.page.extra-service.add',compact('service'));
    }

    public function storeExtraService(Request $request)
    {


        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
//                'comment' => 'required|string',
                'min_price' => 'required|integer',
                'service_id' => 'required|integer',
            ]);

            ExtraService::create($validatedData);

            Session::flash('toaster', ['status' => 'success', 'message' => 'Extra Service saved successfully']);

            return redirect()->route('list-extra');
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

    }

    public function extraList(){
        $extras = ExtraService::with('service')->get();
        return view('admin.page.extra-service.list',compact('extras'));

    }

    public function updateExtraService(Request $request, $id)
    {
        try {
            $extraService = ExtraService::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required|string',
                'min_price' => 'required|integer',
                'service_id' => 'required|integer',
            ]);

            $extraService->update($validatedData);
            Session::flash('toaster', ['status' => 'success', 'message' => 'Extra Service update successfully']);
            return redirect()->route('list-extra');
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
    }
    public function serviceEidt($id){
       $service = Service::findOrFail($id);
       $zones = Zone::all();
       $categories = Category::whereIsActive('1');
       $subcategories = Category::where('parent_id','!=',0)->get();
       $providers = Provider::whereIsActive('1')->get();
       return view('admin.page.service.edit',compact('service','zones','categories','subcategories','providers'));
    }
    public function edit($id)
    {
        $extraService = ExtraService::find($id);
        $services = Service::all();

        return view('admin.page.extra-service.edit', compact('extraService', 'services'));
    }

    public function destroyExtraService($id)
    {
        try {
            $extraService = ExtraService::findOrFail($id);
            $extraService->delete();
            Session::flash('toaster', ['status' => 'success', 'message' => 'Extra Service delete successfully']);
            return redirect()->route('list-extra');
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
    }
    public function getServicesByZone($zone){
        $categories = Service::where('status', 1)
            ->where('zone_id',$zone)
            // Check if zone_id is in the 'zone' column
            ->get();
        return response()->json($categories);

    }
    public function getCategoriesByZone($zone)
    {

        $categories = Category::where('parent_id', 0)
            ->whereRaw("FIND_IN_SET($zone, zone_id)") // Check if zone_id is in the 'zone' column
            ->get();
        return response()->json($categories);
    }
    public function getProvidersByZone(Zone $zone)
    {
        $providers = Provider::where('zone_id', $zone->id)->get();
        return response()->json($providers);
    }


}
