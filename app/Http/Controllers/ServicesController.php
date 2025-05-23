<?php

namespace App\Http\Controllers;

use App\Mail\ServiceNotificationEmail;
use App\Models\Category;
use App\Models\SavedService;
use App\Models\Service;
use App\Models\ServiceApplication;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Mail;

class ServicesController extends Controller
{
    //this method will show the services page
    public function index(Request $request) {

        $categories = Category::where('status',1)->get();
        $serviceTypes = ServiceType::where('status',1)->get();

        $services = Service::where('status',1);

        //search using keyword 

        if (!empty($request->keyword)) {
            $services = $services->where(function($query) use ($request) {
                $query->orWhere('title','like','%'.$request->keyword.'%');
                $query->orWhere('keywords','like','%'.$request->keyword.'%');
            });
        }

        //search using location
        if(!empty($request->location)) {
            $services = $services->where('location',$request->location);
        }
        //search using category
        if(!empty($request->category)) {
            $services = $services->where('category_id',$request->category);
        }

        $serviceTypeArray = [];
        //search using serviceType
        if(!empty($request->serviceType)) {
            $serviceTypeArray = explode(',',$request->serviceType);

            $services = $services->whereIn('service_type_id',$serviceTypeArray);
        }

        //search using experience
        if(!empty($request->experience)) {
            $services = $services->where('experience',$request->experience);
        }

        $services = $services->with(['serviceType','category']);

        if($request->sort == '') {
            $services = $services->orderBy('created_at','ASC');
        } else {
            $services = $services->orderBy('created_at','DESC');
        }
        
        
        $services = $services->paginate(6);


        return view('front.services',[
            'categories' => $categories,
            'serviceTypes' => $serviceTypes,
            'services' => $services,
            'serviceTypeArray' => $serviceTypeArray
        ]);
    }

    //this method will show service detail page
    public function detail($id) {

        $service = Service::where([
                                    'id' => $id, 
                                    'status' => 1
                                ])->with(['serviceType','category'])->first();

        if ($service == null) {
            abort(404);
        }

        $count = 0;
        if(Auth::user()){
            $count = SavedService::where([
                'user_id' => Auth::user()->id,
                'service_id' => $id
            ])->count();
        }
        
        // fetch applicants
        
        $applications = ServiceApplication::where('service_id',$id)->with('user')->get();
        

        return view('front.serviceDetail',[ 'service' => $service, 
                                            'count' =>$count, 
                                            'applications' => $applications
                                        ]);
    }

    public function applyService(Request $request) {
        $id = $request->id;
        $service = Service::where('id',$id)->first();

        //if service is not found in db
        
        if ($service == null) {
            $message = 'Service does not exist';
            session()->flash('error',$message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        //you can not apply on your own service
        $employer_id = $service->user_id;

        if ($employer_id == Auth::user()->id) {
            $message = 'You can not apply on your own service.';            
            session()->flash('error',$message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        //you can not apply twice on one service
        $serviceApplicationCount = ServiceApplication::where([
            'user_id' => Auth::user()->id,
            'service_id' => $id
        ])->count();
        

        if ($serviceApplicationCount > 0) {
            $message = 'You already applied on this service.';
            session()->flash('error',$message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        $application = new ServiceApplication();
        $application->service_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        $application->save();


        
        $message = 'You have successfully applied.';

        session()->flash('success', $message);
        
        return response()->json([
                'status' => true,
                'message' => $message
            ]);
        //Send Notification Email to Employer
        $employer = User::where('id',$employer_id)->first();
            
        $mailData = [
            'employer' => $employer,
            'user' => Auth::user(),
            'service' => $service,

        ];
        Mail::to($employer->email)->send(new ServiceNotificationEmail($mailData));

    }

    public function saveService(Request $request) {

        //You can not save your own service.
        $id = $request->id;
        $service = Service::where('id',$id)->first();
        $employer_id = $service->user_id;

        if ($employer_id == Auth::user()->id) {
            $message = 'You can not save your own service.';            
            session()->flash('error',$message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }


        $id = $request->id;
        $service = Service::find($id);
        if ($service == null) {
            session()->flash('error','Service not found');

            return response()->json([
                'status' => false,
            ]);
        }

        //check if user already saved the Service
        $count = SavedService::where([
            'user_id' => Auth::user()->id,
            'service_id' => $id
        ])->count();
        
        if ($count > 0) {
            session()->flash('error','You already saved this service.');

            return response()->json([
                'status' => false,
            ]);
        }

        $savedService = new SavedService;
        $savedService->service_id = $id;
        $savedService->user_id = Auth::user()->id;
        $savedService->save();

        session()->flash('success','Service saved successfully.');

            return response()->json([
                'status' => true,
            ]);
    }
}
