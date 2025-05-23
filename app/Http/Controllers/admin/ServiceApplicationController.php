<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceApplication;
use Illuminate\Http\Request;

class ServiceApplicationController extends Controller
{
    public function index() {
        $applications = ServiceApplication::orderBy('created_at','DESC')
                            ->with('service','user','employer')
                            ->paginate(10);
         return view('admin.service-applications.list',[
                'applications' => $applications 
        ]);
    }

    public function destroy(Request $request) {
        $id = $request->id;

        $serviceApplication = ServiceApplication::find($id);

        if ($serviceApplication == null) {
            session()->flash('error','Either service application delete or not found.');
            return response()->json([
                'status' => false
            ]);
        }

        $serviceApplication->delete();
        session()->flash('success','Service application deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }
}
