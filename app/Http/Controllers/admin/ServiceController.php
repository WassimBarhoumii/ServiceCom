<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index() {
        $services = Service::orderBy('created_at','DESC')->with('user','applications')->paginate(10);
         return view('admin.services.list',[
            'services' => $services
         ]);
    }

    public function edit($id){
        $service = Service::findOrFail($id);

        $categories = Category::orderBy('name','ASC')->get();
        $serviceTypes = ServiceType::orderBy('name','ASC')->get();

        return view('admin.services.edit',[
            'service' => $service,
            'categories' => $categories,
            'serviceTypes' => $serviceTypes,
        ]);
    }

    public function update(Request $request, $id) {

        $rules = [
            'title' => 'required|min:5|max:100',
            'category' => 'required',
            'serviceType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name' => 'required|min:3|max:75',

        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {
            
            $service = Service::find($id);
            $service->title = $request->title;
            $service->category_id = $request->category;
            $service->service_type_id = $request->serviceType;
            $service->vacancy = $request->vacancy;
            $service->salary = $request->salary;
            $service->location = $request->location;
            $service->description = $request->description;
            $service->benefits = $request->benefits;
            $service->responsibility = $request->responsibility;
            $service->qualifications = $request->qualifications;
            $service->keywords = $request->keywords;
            $service->experience = $request->experience;
            $service->company_name = $request->company_name;
            $service->company_location = $request->company_location;
            $service->website = $request->website;

            $service->status = $request->status;
            $service->isFeatured = (!empty($request->isFeatured)) ? $request->isFeatured : 0;
            $service->save();
            
            session()->flash('success', 'Service updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator ->errors()
            ]);
        }
    }

    public function destroy(Request $request) {
        $id = $request->id;

        $Service = Service::find($id);

        if($Service == null) {
            session()->flash('error','Either service deleted or not found');
            return response()->json([
                'status' => false
            ]);
        }

        $Service->delete();
        session()->flash('success','Service deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }
}
