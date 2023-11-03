<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;

class CustomerController extends Controller
{
    public function userList(){
        $customers = Customer::all();

        return view('admin.page.user.list',compact('customers'));
    }
}
