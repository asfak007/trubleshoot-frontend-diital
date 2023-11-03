<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Models\Campaign;
use App\Models\Category;
use App\Models\Service;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

class CampaingController extends Controller
{
    public function add(){
        $zones =Zone::all();
        return view('admin.page.campain.add',compact('zones'));
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'zone_id' => 'required',
                'category_id'=> 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'discount' => 'required|integer',
                'start' => 'required|date',
                'end' => 'required|date|after:start_date',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            if ($request->has('category_id')){
                $validatedData['type']= 'category';
            }
            $validatedData['name'] = $request->name;
            $validatedData['category_id']=$request->category_id;

            $validatedData['zone_id'] = $request->zone_id;
            $validatedData['discount'] = $request->discount;
            $validatedData['start'] = $request->start;
            $validatedData['end'] = $request->end;

            // Create the campaign
            $campaign = Campaign::create($validatedData);
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $destinationPath = public_path('assets/images/campaign/');
                $postImage = "campaign_$campaign->id.png";
                $fullPath = $destinationPath . $postImage;

                // Check if the directory exists, if not create it
                if (!file_exists($destinationPath)) {
                    if (!mkdir($destinationPath, 0777, true) && !is_dir($destinationPath)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $destinationPath));
                    }
                }

                // Save the image to the specified path
                Image::make($image)->resize(200, 200)->save($fullPath);
                 $campaign->image =  '/assets/images/campaign/'.$postImage ;
                $campaign->save();
            }


            // Associate the campaign with categories
//            $campaign->categories()->attach($request->category);

            Session::flash('toaster', ['status' => 'success', 'message' => 'Campaign created successfully!']);


            return redirect()->route('campaign.list');

        } catch (\Illuminate\Validation\ValidationException $e) {
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
    public function serviceStore(Request $request){
        try {

            // Data validation
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'zone_id' => 'required|integer',
                'service_id' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'discount' => 'required|integer',
                'start' => 'required|date',
                'end' => 'required|date|after:start_date',

            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $campaignData = $request->all();
            $campaignData['type'] = 'service';
            $campaign = Campaign::create($campaignData);

            // If an image file is provided, store it and get the path
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $destinationPath = public_path('assets/images/campaign/');
                $postImage = "campaign_$campaign->id.png";
                $fullPath = $destinationPath . $postImage;

                // Check if the directory exists, if not create it
                if (!file_exists($destinationPath)) {
                    if (!mkdir($destinationPath, 0777, true) && !is_dir($destinationPath)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $destinationPath));
                    }
                }

                // Save the image to the specified path
                Image::make($image)->resize(200, 200)->save($fullPath);
                $campaign->image =  '/assets/images/campaign/'.$postImage ;
                $campaign->save();
            }

            // Convert category_ids and service_id arrays to JSON string
//            $campaignData['service_id'] = $campaignData['service_id'];

            // Create the campaign


            Session::flash('toaster', ['status' => 'success', 'message' => 'Campaign created successfully!']);


            return redirect()->route('campaign.list');

        } catch (\Illuminate\Validation\ValidationException $e) {
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
    public function serviceCampaignAdd(Request $request){
        $zones = Zone::all();
        return view('admin.page.campain.service-add',compact('zones'));
    }

    public function destroy($id)
    {
        try {
            // Find the category by ID
            $campaign = Campaign::findOrFail($id);
            if ($campaign->image) {
                $path = public_path('assets/images/campaign/' . $campaign->image);
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // Delete the campaign
            $campaign->delete();

            Session::flash('toaster', ['status' => 'success', 'message' => 'Campaign deleted successfully!']);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the deletion process
            Session::flash('toaster', ['status' => 'error', 'message' => 'Failed to delete campaign!']);
        }
        // Find the campaign by ID


        return redirect()->back();
    }

    public function campaignlist()
    {
        $campaigns = Campaign::with('zone','service')->get()->map(function ($campaign) {
            $campaign->categories =  Category::where('id', $campaign->category_id)->get();
            return $campaign;
        });

        return view('admin.page.campain.list',compact('campaigns'));
    }


}
