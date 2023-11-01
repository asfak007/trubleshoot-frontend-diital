<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('admin.page.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            // Validate the input
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'phone' => 'nullable|string|max:20',
                'old_password' => 'required_with:new_password|password',
                'new_password' => 'nullable|string|min:8|confirmed',
            ]);

            // Update user information
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $destinationPath = public_path('assets/images/user/');
                $postImage = "user_$user->id.png";
                $fullPath = $destinationPath . $postImage;

                // Check if the directory exists, if not create it
                if (!file_exists($destinationPath)) {
                    if (!mkdir($destinationPath, 0777, true) && !is_dir($destinationPath)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $destinationPath));
                    }
                }

                // Save the image to the specified path
                Image::make($image)->resize(200, 200)->save($fullPath);
                $user->image = '/assets/images/user/'.$postImage;

            }

            if ($request->filled('new_password')) {
                if (Hash::check($request->input('old_password'), $user->password)) {
                    $user->password = Hash::make($request->input('new_password'));
                } else {
                    Session::flash('toaster', ['status' => 'error', 'message' => 'The old password is incorrect.']);
                    return redirect()->back();
                }
            }

            $user->save();
            Session::flash('toaster', ['status' => 'success', 'message' => 'Profile update successfully']);

            return redirect()->route('profile-show');
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
}
