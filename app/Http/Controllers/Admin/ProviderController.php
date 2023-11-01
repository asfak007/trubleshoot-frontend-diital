<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

class ProviderController extends Controller
{
    public function providerAdd(){
        $zones = Zone::get();
        return view('admin.page.providor.add',compact('zones'));
    }
    public function providerStore(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:providers',
                'phone' => 'required|string|max:15',
                'zone' => 'required|exists:zones,id',
                'identity_type' => 'required|string|max:255',
                'identity_number' => 'required|string|max:255',
                'contact_person_name' => 'required|string|max:255',
                'contact_person_phone' => 'required|string|max:15',
                'contact_email' => 'required|string|email|max:255',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'Password' => 'required|string|min:8|max:255',
                'start' => 'required',
                'end' => 'required',
                'image' => 'nullable|file|mimes:jpg,png,jpeg,gif,svg|max:2048',
                'identity_image1' => 'nullable',
                'identity_image1.*' => 'file|mimes:jpg,png,jpeg,gif,svg|max:2048',
                'identity_image2' => 'nullable',
                'identity_image2.*' => 'file|mimes:jpg,png,jpeg,gif,svg|max:2048',
                'document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $data = [
                'company_name' => $request->company_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'zone_id' => $request->zone,
                'identity_type' => $request->identity_type,
                'identity_number' => $request->identity_number,
                'contact_person_name' => $request->contact_person_name,
                'contact_person_phone' => $request->contact_person_phone,
                'contact_email' => $request->contact_email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'start'=> $request->start,
                'end'=> $request->end,
                'is_approved' => 1
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = public_path('assets/images/provider/') . $imageName;

                // Create the "public/images" directory if it doesn't exist
                if (!file_exists(public_path('assets/images/provider/') )) {
                    if (!mkdir(public_path('assets/images/provider/') , 0777, true) && !is_dir(public_path('assets/images/provider/') )) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', public_path('assets/images/provider/') ));
                    }
                }

                // Save the image to the specified path
                Image::make($image)->resize(200, 200)->save($imagePath);

                $data['image'] = $imageName;
            }
            if ($request->hasFile('identity_image1')) {
                $identityImage1 = $request->file('identity_image1');
                $destinationPath = public_path('assets/images/providerIdentity/');
                if (!file_exists($destinationPath )) {
                    if (!mkdir($destinationPath , 0777, true) && !is_dir($$destinationPath )) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $destinationPath ));
                    }
                }
                $imageName = time() . '_' . $identityImage1->getClientOriginalName();
                $fullPath = $destinationPath . $imageName;
                Image::make($identityImage1)->resize(200, 200)->save($fullPath, 100,'png');
                $data['identity_image1'] = '/assets/images/providerIdentity/'.$imageName;
            }




            // Handle identity image 2
            if ($request->hasFile('identity_image2')) {
                $identityImage2 = $request->file('identity_image2');
                $destinationPath = public_path('assets/images/providerIdentity/');
                $imageName = time() . '_' . $identityImage2->getClientOriginalName();
                $fullPath = $destinationPath . $imageName;

                Image::make($identityImage2)->resize(200, 200)->save($fullPath, 100,'png');

                $data['identity_image2'] = '/assets/images/providerIdentity/'.$imageName;
            }

            // Handle provider document
            if ($request->hasFile('document')) {
                $document = $request->file('document');
                $companyName = str_replace(' ', '_', $request->input('company_name'));
                $documentName = $companyName . '_' . time() . '.' . $document->getClientOriginalExtension(); // Add timestamp to ensure uniqueness
                $documentPath = 'assets/images/providerDocuments/' . $documentName;

                // Save the document to the public path
                $document->move(public_path('assets/images/providerDocuments'), $documentName);

                $data['document'] = $documentName;
            }

            $data['password'] = Hash::make($request->Password);
//            dd($data) ;

            $provider = Provider::create($data);

            // Associate identity images and document with the provider document model
            if ($provider) {
                $provider->documents()->create([
                    'type' => 'image',
                    'file_path' => json_encode([
                        'front' => $data['identity_image1'],
                        'cover' => $data['identity_image2'],
                    ]),
                ]);

                if ($data['document']) {
                    $provider->documents()->create([
                        'type' => 'document',
                        'file_path' => $data['document'],
                    ]);
                }
            }

            Session::flash('toaster', ['status' => 'success', 'message' => 'Provider created successfully']);

            return redirect()->route('provider.list');
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

    public function providerlist($slug='default'){

        $providers = Provider::withCount('services')->when(function($q) use($slug) {
            $types= [
                "active",
                "inactive",
                "onboarding"
            ];
            if(in_array($slug,$types)){
                if($slug=="active"){
                   return $q->where("is_active", 1);
                }
                if($slug=="inactive"){
                    return $q->where("is_active", 0);
                }if($slug=="onboarding"){
                    return $q->where("is_approved", 0);
                }
            }

            return  $q;
        })->get();


        $onboardingRequests = Provider::where('is_approved', '0')->count();
        $inactiveProviders = Provider::where('is_active', '0')->count();
        $activeProviders = Provider::where('is_active', '1')->count();
        $totalProviders = Provider::all()->count();
        return view('admin.page.providor.list',compact('providers', 'totalProviders', 'onboardingRequests', 'inactiveProviders', 'activeProviders'));
    }
    public function delete($id)
    {
        // Find the provider by ID
        $provider = Provider::find($id);

        // Check if the provider exists
        if (!$provider) {
            Session::flash('toaster', ['status' => 'success', 'message' => 'Provider deleted failed']);

            return redirect()->route('provider.list');
        }

        // Delete the provider
        $provider->delete();
        Session::flash('toaster', ['status' => 'success', 'message' => 'Provider deleted successfully']);
        return redirect()->route('provider.list');
    }
}
