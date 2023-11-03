<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CouponController extends Controller
{
    public function addCoupon(){
        $providers = Provider::where('is_active',1)->get();
        return view('admin.page.coupon.add',compact('providers'));
    }
    public function listCoupon(){
        $coupons = Coupon::with('provider')->get()->all();

        return view('admin.page.coupon.list',compact('coupons'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'code' => 'required|unique:coupons,code',
                'discount' => 'required|numeric',
                'start' => 'required|date|after_or_equal:today',
                'end' => 'required|date|after:start',
                'min_amount' => 'required|numeric',
                'provider_id' => 'required|exists:providers,id',
            ]);

            Coupon::create($data);
            // Your logic here

            return redirect()->route('coupon-list')
                ->with('toaster', ['status' => 'success', 'message' => 'Coupon added successfully']);

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

    public function edit($id)
    {
        // Fetch the coupon using the provided ID
        $coupon = Coupon::findOrFail($id);

        // Fetch the providers or any other necessary data
        $providers = Provider::all();

        // Pass the coupon to the view
        return view('admin.page.coupon.edit', compact('coupon', 'providers'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        try {
            $data = $request->validate([
                'name' => 'sometimes|string',
                'code' => 'sometimes|unique:coupons,code,' . $coupon->id,
                'discount' => 'sometimes|numeric',
                'start' => 'sometimes|date',
                'end' => 'sometimes|date',
                'min_amount' => 'sometimes|numeric',
                'provider_id' => 'sometimes|exists:providers,id',
            ]);

            $coupon->update($data);
            Session::flash('toaster', ['status' => 'success', 'message' => 'Coupon update successfully']);

        }catch (\Exception $e){
            Session::flash('toaster', ['status' => 'error', 'message' => 'Failed to delete coupon!']);
        }

        return redirect()->route('coupon-list');
    }

    public function destroy(Coupon $coupon)
    {


        try {
            // Find the category by ID
            $coupon->delete();

            Session::flash('toaster', ['status' => 'success', 'message' => 'Coupon deleted successfully!']);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the deletion process
            Session::flash('toaster', ['status' => 'error', 'message' => 'Failed to delete coupon!']);
        }

        return redirect()->route('coupon-list');
    }


}
