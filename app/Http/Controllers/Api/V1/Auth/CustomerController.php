<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Events\RegisteredCustomer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response as HTTP;
use Illuminate\Support\Facades\Http as Https;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Support\Facades\Http as Client;
use App\Http\Resources\Api\V1\CustomerResource;
use App\Http\Requests\Api\V1\CustomerLoginRequest;

class CustomerController extends Controller
{

    /**
     * Retrieve customer info.
     */
    public function customer(Request $request)
    {
        try {
            $customer = $request->user('customers');
            // $customer->load(["address", "bookings"]);
            $customer->load("address");
            return Response::json([
                'success'   => true,
                'status'    => HTTP::HTTP_OK,
                'message'   => "Customer successfully authorized.",
                'info'   => new CustomerResource($customer)
            ],  HTTP::HTTP_OK); // HTTP::HTTP_OK
        } catch (\Exception $e) {
            //throw $e;
            return Response::json([
                'success'   => false,
                'status'    => HTTP::HTTP_FORBIDDEN,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],  HTTP::HTTP_FORBIDDEN); // HTTP::HTTP_OK
        }
    }

    /**
     * Validate OTP.
     */
    public function validateOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'phone' => 'required|string|min:10|max:15',
            'phone' => [
                'required', 'string', "min:10", "max:15",
                Rule::exists('customers', 'phone'),
            ],
            'otp' => 'required|string',
        ], [
            'phone.exists' => 'Customer not found.',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'success'   => false,
                'status'    => HTTP::HTTP_UNPROCESSABLE_ENTITY,
                'message'   => "Validation failed.",
                'errors' => $validator->errors()
            ],  HTTP::HTTP_UNPROCESSABLE_ENTITY); // HTTP::HTTP_OK
        }

        try {
            // Find the customer by email
            $customer = Customer::where('phone', $request->phone)->first();

            // Check if the provided OTP matches the stored OTP
            // if ($customer->otp != $request->otp) {
            //     return Response::json([
            //         'success'   => false,
            //         'status'    => HTTP::HTTP_UNAUTHORIZED,
            //         'message'   => "OTP didn't matched.",
            //         'errors'     => "Invalid OTP."
            //     ],  HTTP::HTTP_UNAUTHORIZED); // HTTP::HTTP_OK
            // }

            if ($customer->otp != $request->otp) {
                return Response::json([
                    'success'   => false,
                    'status'    => HTTP::HTTP_UNAUTHORIZED,
                    'message'   => "OTP didn't matched.",
                    'errors'     => "Invalid OTP."
                ],  HTTP::HTTP_UNAUTHORIZED); // HTTP::HTTP_OK
            }


            // If the OTP matches, update the phone_verify field to 1
            $customer->phone_verify = true;
            // $customer->otp = null;
            $customer->save();

            return Response::json([
                'success'   => true,
                'status'    => HTTP::HTTP_OK,
                'message'   => "Phone number verified successfully.",
            ],  HTTP::HTTP_OK); // HTTP::HTTP_OK
        } catch (\Exception $e) {
            //throw $e;
            return Response::json([
                'success'   => false,
                'status'    => HTTP::HTTP_FORBIDDEN,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],  HTTP::HTTP_FORBIDDEN); // HTTP::HTTP_OK
        }
    }

    /**
     * Regenerate OTP.
     */
    public function regenerateOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'phone' => 'required|string|min:10|max:15',
            'phone' => [
                'required', 'string', "min:10", "max:15",
                Rule::exists('customers', 'phone'),
            ],
        ], [
            'phone.exists' => 'Customer not found.',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'success'   => false,
                'status'    => HTTP::HTTP_UNPROCESSABLE_ENTITY,
                'message'   => "Validation failed.",
                'errors' => $validator->errors()
            ],  HTTP::HTTP_UNPROCESSABLE_ENTITY); // HTTP::HTTP_OK
        }

        try {
            // Find the customer by phone number
            $customer = Customer::where('phone', $request->phone)->first();

            if ($customer->phone_verify) {
                return Response::json([
                    'success'   => true,
                    'status'    => HTTP::HTTP_OK,
                    'message'   => "Phone number is already verified.",
                ],  HTTP::HTTP_OK); // HTTP::HTTP_OK
            }
            // Generate a new 4-digit random OTP
            $otp = str_pad(mt_rand(0, 999999), 6);

            // Update the customer's OTP in the database
            $customer->otp = $otp;
            $customer->save();

            // SMS service for sending OTPs to the phone
            $this->sendOtpToPhone($customer->phone, $otp);
        } catch (\Exception $e) {
            //throw $e;
            return Response::json([
                'success'   => false,
                'status'    => HTTP::HTTP_FORBIDDEN,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],  HTTP::HTTP_FORBIDDEN); // HTTP::HTTP_OK
        }
    }

    /**
     * Logout customer.
     */
    public function logout(Request $request)
    {
        try {
            if ($request->user('customers')) {
                $request->user('customers')->tokens()->delete();
                return Response::json([
                    'success'   => true,
                    'status'    => HTTP::HTTP_OK,
                    'message'   => "Logged out successfully.",
                ],  HTTP::HTTP_OK); // HTTP::HTTP_OK
            } else {
                return Response::json([
                    'success'   => true,
                    'status'    => HTTP::HTTP_OK,
                    'message'   => "You have already logged out.",
                ],  HTTP::HTTP_OK); // HTTP::HTTP_OK
            }
        } catch (\Exception $e) {
            //throw $e;
            return Response::json([
                'success'   => false,
                'status'    => HTTP::HTTP_FORBIDDEN,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],  HTTP::HTTP_FORBIDDEN); // HTTP::HTTP_OK
        }
    }

    /**
     * Customer Login with mail and phone.
     */
    public function login(CustomerLoginRequest $request)
    {
        $loginField = $request->input('login_field');
        $password = $request->input('password');

        try {
            $credentials = [];
            if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
                // The input is an email
                $credentials['email'] = $loginField;
            } else {
                // The input is a phone number
                $credentials['phone'] = $loginField;
            }

            $customer = Customer::where($credentials)->first();

            if (!$customer || !Hash::check($password, $customer->password)) {
                return Response::json([
                    'success'   => false,
                    'status'    => HTTP::HTTP_UNAUTHORIZED,
                    'message'   => "Unauthenticated customer credentials.",
                    'errors' => 'Invalid credentials.'
                ],  HTTP::HTTP_UNAUTHORIZED); // HTTP::HTTP_OK
            }
            if ($customer->phone_verify === 0) {
                return Response::json([
                    'success'   => false,
                    'status'    => HTTP::HTTP_FORBIDDEN,
                    'message'   => "Customer phone is not verified.",
                    'errors' => 'Phone is not verified.'
                ],  HTTP::HTTP_FORBIDDEN); // HTTP::HTTP_OK
            }
            // $request->user('customers')->tokens()->delete();

            // $customer->tokens()->delete(); // uncomment for live server
            $token = $customer->createToken('authToken')->plainTextToken;
            Session::put("customer", $token); // for next.js frontend

            return Response::json([
                'success'   => true,
                'status'    => HTTP::HTTP_OK,
                'message'   => "Customer successfully authorized.",
                'token' => $token
            ],  HTTP::HTTP_OK); // HTTP::HTTP_OK
        } catch (\Exception $e) {
            //throw $e;
            return Response::json([
                'success'   => false,
                'status'    => HTTP::HTTP_FORBIDDEN,
                'message'   => "Something went wrong. Try after sometimes.",
                'err' => $e->getMessage(),
            ],  HTTP::HTTP_FORBIDDEN); // HTTP::HTTP_OK
        }
    }

    /**
     * Crete a newly created customer in database.
     */
    public function register(StoreCustomerRequest $request)
    {
        $address = new Address();
        $address->lat = $request->lat;
        $address->lng = $request->lng;
        $address->street_one = "";
        $address->street_two = "";
        $address->apartment_name = "";
        $address->apartment_number = "";
        $address->city = "";
        $address->zip = "";
    
        try {
            $response = Http::withOptions(["verify" => false])->acceptJson()->get("http://ip-api.com/json", [
                "lat" => $request->lat,
                "lng" => $request->lng,
            ]);
    
            if ($response->successful()) {
                $address->city = $response->json("city");
                $address->zip = $response->json("zip");
            }
        } catch (\Exception $e) {
            // Handle the exception as needed
        }
    
        try {
            // If the user is not registered, proceed with registration
            $otp = str_pad(mt_rand(0, 999999), 6);
            
            $phone = $request->phone;
            $ttl = 1; // 1 min lock for OTP
    
            // Start sending OTP
            $otpSent = $this->sendOtp($phone, $otp);
    
            if (!$otpSent) {
                // Handle OTP sending failure
                return response()->json([
                    'success' => false,
                    'status' => HTTP::HTTP_FORBIDDEN,
                    'message' => "Failed to send OTP. Please try again later.",
                ], HTTP::HTTP_FORBIDDEN);
            }
    
            $customer = Customer::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => $request->input("status", false),
                'ref' => $this->generateUniqueRefCode($request->phone),
                'otp' => $otp, // Save the generated OTP
            ]);
    
            event(new RegisteredCustomer($customer));
    
            $address->customer_id = $customer->id;
            $address->save();
    
            // Handle image upload and update (if applicable)
    
            $token = $customer->createToken('authToken')->plainTextToken;
            return response()->json([
                'success'   => true,
                'status'    => HTTP::HTTP_CREATED,
                'message'   => "Customer registered successfully.",
                "verify_token" => $token
            ], HTTP::HTTP_CREATED);
        } catch (\Exception $e) {
            // Handle exceptions for registration
            return response()->json([
                'success'   => false,
                'status'    => HTTP::HTTP_FORBIDDEN,
                'message'   => "Something went wrong. Try again later.",
                'err' => $e->getMessage(),
            ], HTTP::HTTP_FORBIDDEN);
        }
    }

    /**
     * Update customer data database.
     */
    public function update(UpdateCustomerRequest $request)
    {
        // get customer
        $customer = $request->user('customers');

        $credentials = Arr::only($request->all(), [
            'first_name',
            'last_name',
            'email',
            'image',
        ]);

        try {
            // Handle image upload and update
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = "customer_$customer->id.png";
                $imagePath = "assets/images/customer/$imageName";

                try {
                    // Create the "public/images" directory if it doesn't exist
                    if (!File::isDirectory(public_path("assets/images/customer"))) {
                        File::makeDirectory((public_path("assets/images/customer")), 0777, true, true);
                    }

                    // Save the image to the specified path
                    $image->move(public_path('assets/images/customer'), $imageName);
                    $credentials["image"] = $imagePath;
                } catch (\Exception $e) {
                    // throw $e;
                    // skip if not uploaded
                }
            }


            // Update the customer data
            $customer->update($credentials);

            return Response::json([
                'success'   => true,
                'status'    => HTTP::HTTP_OK,
                'message'   => "Profile updated successfully.",
            ],  HTTP::HTTP_OK); // HTTP::HTTP_OK
        } catch (\Exception $e) {
            throw $e;
            return Response::json([
                'success'   => false,
                'status'    => HTTP::HTTP_FORBIDDEN,
                'message'   => "Something went wrong. Try after sometimes.",
                // 'err' => $e->getMessage(),
            ],  HTTP::HTTP_FORBIDDEN); // HTTP::HTTP_OK
        }
    }


    /**
     * Delete customer from database.
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'success'   => false,
                'status'    => HTTP::HTTP_UNPROCESSABLE_ENTITY,
                'message'   => "Validation failed.",
                'errors' => $validator->errors()
            ],  HTTP::HTTP_UNPROCESSABLE_ENTITY); // HTTP::HTTP_OK
        }

        // get customer
        $customer = $request->user('customers');

        try {
            // Verify the provided password
            if (password_verify($request->password, $customer->password)) {
                // Delete the account from the database
                $customer->tokens()->delete();
                $customer->delete();
            } else {
                return Response::json([
                    'success'   => false,
                    'status'    => HTTP::HTTP_UNPROCESSABLE_ENTITY,
                    'message'   => "Invalid password. Please try again.",
                ],  HTTP::HTTP_UNPROCESSABLE_ENTITY); // HTTP::HTTP_OK
            }

            return Response::json([
                'success'   => true,
                'status'    => HTTP::HTTP_ACCEPTED,
                'message'   => "Account deleted successfully.",
            ],  HTTP::HTTP_ACCEPTED); // HTTP::HTTP_OK
        } catch (\Exception $e) {
            //throw $e;
            return Response::json([
                'success'   => false,
                'status'    => HTTP::HTTP_FORBIDDEN,
                'message'   => "Something went wrong. Try after sometimes.",
                // 'err' => $e->getMessage(),
            ],  HTTP::HTTP_FORBIDDEN); // HTTP::HTTP_OK
        }
    }

    /**
     * Generate a unique ref code for customer.
     */
    private function generateUniqueRefCode($ref)
    {
        $ref_code = substr($ref, 3); // Generate a 10-character random string in uppercase
        while (Customer::where('ref', $ref_code)->exists()) {
            // Check if the generated code already exists in the database
            $ref_code .= rand(0, 9);
        }
        return $ref_code;
    }

    /**
     * Generate a unique ref code for customer.
     */
    private function sendOtpToPhone($phone, $otp, $ttl = 1)
    {
        if (!Cache::has("$phone")) {
            try {
                Cache::remember("$phone", 60 * $ttl, function () { // disabled for 2 minutes
                    return true;
                });

                // start::sending otp
                $this->sendOtp($phone, $otp);
                // end::sending otp

                return Response::json([
                    "success" => true,
                    'status'  => HTTP::HTTP_OK,
                    "message" => "We have sent OTP successfully.",
                ], HTTP::HTTP_OK);
            } catch (\Exception $e) {
                //throw $e;
                return Response::json([
                    'success'   => false,
                    'status'    => HTTP::HTTP_FORBIDDEN,
                    "message" => 'Something went wrong. Please try again after few min.',
                    // 'err' => $e->getMessage(),
                ],  HTTP::HTTP_FORBIDDEN); // HTTP::HTTP_OK
            }
        }

        return Response::json([
            "success" => false,
            'status'  => HTTP::HTTP_BAD_REQUEST,
            "message" => "Please try again after $ttl min.",
        ], HTTP::HTTP_OK);
    }

    /**
     * Generate a unique ref code for customer.
     */
    private function sendOtp($phone, $otp)
    {
        // $otp = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Build the URL for the one-to-many SMS API
        $url = 'https://api.rtcom.xyz/onetomany';

        // Prepare the request payload
        $requestData = [
            'acode' => 30000080,
            'api_key' => '4c932c955432daaaf796ea7f53f5e4a504f08e03',
            'senderid' => 8809617612721,
            'type' => 'text',
            'msg' => 'Your OTP is: ' . $otp,
            'contacts' => $phone, // Use the provided phone number
            'transactionType' => 'T', // Transactional SMS
            'contentID' => '', // Empty for Transactional
        ];

        // Send the HTTP request to the SMS API
        $response = Https::post($url, $requestData);

        if ($response->successful()) {
            // SMS sent successfully
            // You can save the OTP in your database for verification
            // For example, save the $otp and the user's phone number in your users table

            // Return a success response to the user
            return true;
        } else {
            // SMS sending failed
            // Handle the error and return an error response
            return false; // Use an appropriate HTTP status code
        }
    }
    
    
    
    public function changePassword(Request $request){
        try {
            // Define the validation rules here
            $rules = [
                'old-password' => 'required|min:8',
                'password' => 'required|min:8',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'status' => 422, // Change to an appropriate status code for validation failure
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = $request->user('customers');
            $password = $user->getAuthPassword();

            if (Hash::check($request->input('old-password'), $password)){
                $user->password = Hash::make($request->input('password'));
                $user->save();

                return response()->json([
                    'success' => true,
                    'status' => 202,
                    'message' => 'Customer password updated successfully.',
                ], 202);
            }

            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => "Old password doesn't match.",
            ], 403);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => "Something went wrong. Try again later.",
                'err' => $e->getMessage(),
            ], 403);
        }
    }
    
    
}