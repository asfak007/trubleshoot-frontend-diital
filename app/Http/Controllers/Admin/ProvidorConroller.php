<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Zone;
use Illuminate\Http\Request;

class ProvidorConroller extends Controller
{
    public function providerAdd(){
        $zones = Zone::get();
        return view('admin.page.providor.add',compact('zones'));
    }
    public function providerStore(Request $request)
    {
        return $request->all();
    }
    public function providerlist(){
        $providers = Provider::get();
        return view('admin.page.providor.list',compact('providers'));
    }
}
