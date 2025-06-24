<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\Category;
use App\Models\SavedService;
use App\Models\Service;
use App\Models\ServiceApplication;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use SebastianBergmann\Type\TrueType;

class AccountController extends Controller
{   
    //this method will show the registration page 
    public function registration() {
        return view('front.account.registration');
    }

    //this method will save a user
    public function processRegistration(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|same:confirm_password',
            'confirm_password' => 'required',            

        ]);

        if ($validator->passes()) {

            $user = new User();
            $user ->name = $request -> name;
            $user ->email = $request -> email;
            $user ->password = Hash::make($request -> password);
            $user ->name = $request -> name;
            $user ->save();

            session()->flash('success','You have registred successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    //this method will show the login page
    public function login() {
        return view('front.account.login');

    }
    //this method will authenticate a user
    public function authenticate(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {

            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('account.profile');
            } else {
                return redirect()-> route('account.login')->with('error','Either Email Or Password is incorrect');
            }
        } else {
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }

    //this method will show the user profile page
    public function profile() {

        $id = Auth::user()->id;
        
        $user = User::where('id',$id)->first();   

        return view('front.account.profile',[
            'user' => $user
        ]); 
    }

    //this method will update the user profile
    public function updateProfile(Request $request) {
        
        $id = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users,email,'.$id.',id'
        ]);
        if ($validator->passes()) {
            
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->designation = $request->designation;
            $user->save();

            session()->flash('success','Profile updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    //this method will logout a user
    public function logout() {
        Auth::logout();
        return redirect()->route('account.login');
    }

    //this method will update the profile picture
    public function updateProfilePic(Request $request) {
        //dd($request->all());
        $id = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'image' => 'required|image'
        ]);
        
         if ($validator->passes()) {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id.'-'.time().'.'.$ext;
            $image->move(public_path('/profile_pic/'), $imageName);

            //create a small thumbnail
            $sourcePath = public_path('/profile_pic/'.$imageName);
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($sourcePath);

            // crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
            $image->cover(150, 150);
            $image->toPng()->save(public_path('/profile_pic/thumb/'.$imageName));

            //Delete old profile pic

            File::delete(public_path('/profile_pic/thumb/'.Auth::user()->image));
            File::delete(public_path('/profile_pic/'.Auth::user()->image));

            User::where('id',$id)->update(['image' => $imageName]);

            session()->flash('success', 'Profile picture updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

         } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
         }
    }

    //this method will show the create service page
    public function createService() {

        $categories = Category::orderBy('name','ASC')->where('status',1)->get();

        $serviceTypes = ServiceType::orderBy('name','ASC')->where('status',1)->get();


        return view('front.account.service.create',[
            'categories' => $categories,
            'serviceTypes' => $serviceTypes,
        ]);
    }

    //this method will save a service
    public function saveService(Request $request) {
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
            $service = new Service();
            $service->title = $request->title;
            $service->category_id = $request->category;
            $service->service_type_id = $request->serviceType;
            $service->user_id = Auth::user()->id;
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
            $service->save();
            
            session()->flash('success', 'Service added successfully.');

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

    //this method will show the user's services
    public function myServices() {
        $services = Service::where('user_id', Auth::user()->id)->with('serviceType')->paginate(10);
        return view('front.account.service.my-services',[
            'services' => $services
        ]);
    }

    //this method will show the edit service page
    public function editService(Request $request, $id) {
        
        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        $serviceTypes = ServiceType::orderBy('name','ASC')->where('status',1)->get();

        // Check if the service belongs to the authenticated user
        $service = Service::where([
            'user_id' => Auth::user()->id,
            'id' => $id
        ]) -> first();


        if ($service == null) {
            abort(404);
        }


        return view('front.account.service.edit',[
            'categories' => $categories,
            'serviceTypes' => $serviceTypes,
            'service' => $service,
        ]);
    }

    //this method will update a service
    public function updateService(Request $request, $id) {
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
            // Add ownership validation before updating  
            $service = Service::where([  
                'user_id' => Auth::user()->id,  
                'id' => $id  
            ])->first();  
              
            if ($service == null) {  
                abort(404);  
            }  
              
            // Now update the service (remove the Service::find($id) line)  
            $service->title = $request->title;  
            $service->category_id = $request->category;  
            $service->service_type_id = $request->serviceType;  
            $service->user_id = Auth::user()->id;  
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
            $service->save();  
              
            session()->flash('success', 'Service updated successfully.');  
      
            return response()->json([  
                'status' => true,  
                'errors' => []  
            ]);  
      
        } else {  
            return response()->json([  
                'status' => false,  
                'errors' => $validator->errors()  
            ]);  
        }  
    }

    public function deleteService(Request $request) {

        $service = Service::where([
            'user_id' => Auth::user()->id,
            'id' => $request->serviceId
        ])->first();

        if ($service == null) {
            session()->flash('error','Either service deleted or not found.');
            return response()->json([
                'status' => true
            ]);
        }

        Service::where('id',$request->serviceId)->delete();
        session()->flash('success','Service deleted successfully.');
            return response()->json([
                'status' => true
            ]);
    }

    public function myServiceApplications() {
        $serviceApplications = ServiceApplication::where('user_id',Auth::user()->id)
            ->with(['service','service.serviceType'])
            ->orderBy('created_at','DESC')
            ->paginate(10);
        return view('front.account.service.my-service-applications',[
            'serviceApplications' => $serviceApplications
        ]);
    }

    public function removeServices(Request $request) {
        $serviceApplication = ServiceApplication::where([
                                                            'id' => $request->id, 
                                                            'user_id' => Auth::user()->id
                                                        ])->first();
        if ($serviceApplication == null) {
            session()->flash('error','Service application not found');
            return response()->json([
                'status' => false,
            ]);
        }

        ServiceApplication::find($request->id)->delete();
        session()->flash('success','Service application removed successfully.');

        return response()->json([
            'status' => true,
        ]);
    }
    public function savedServices() {
        /* $serviceApplications = ServiceApplication::where('user_id',Auth::user()->id)
        ->with(['service','service.serviceType'])
        ->paginate(10); */

        $savedServices = SavedService::where([
            'user_id' => Auth::user()->id
        ])->with(['service','service.serviceType', 'service.applications'])
        ->orderBy('created_at','DESC')
        ->paginate(20);

        return view('front.account.service.saved-services',[
        'savedServices' => $savedServices
    ]);        
    }

    public function removeSavedService(Request $request) {
        $savedService = SavedService::where([
                                                            'id' => $request->id, 
                                                            'user_id' => Auth::user()->id
                                                        ])->first();
        if ($savedService == null) {
            session()->flash('error','Service not found');
            return response()->json([
                'status' => false,
            ]);
        }

        SavedService::find($request->id)->delete();
        session()->flash('success','Service removed successfully.');

        return response()->json([
            'status' => true,
        ]);
    }

    public function updatePassword(Request $request) {
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        if (Hash::check($request->old_password, Auth::user()->password) == false) {
            session()->flash('error','Old password is incorrect.');
            return response()->json([
                'status' => true
            ]);
        }

        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        session()->flash('success','Password updated successfully.');
        return response()->json([
            'status' => true
        ]);
    }

    public function forgotPassword(){
        return view('front.account.forgot-password');
    }

    public function processForgotPassword(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.forgotPassword')->withInput()->withErrors($validator);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Send email
        $user = User::where('email', $request->email)->first();
        $mailData = [
            'token' => $token,
            'user' => $user,
            'subject' => 'You have requested to reset your password',
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($mailData));

        return redirect()->route('account.forgotPassword')->with('success', 'We have sent you a password reset link. Please check your email.');
    }

    public function resetPassword($tokenString) {
        $token = DB::table('password_reset_tokens')->where('token', $tokenString)->first();

        if ($token == null) {
            return redirect()->route('account.forgotPassword')->with('error', 'Invalid token.');
        }

        return view('front.account.reset-password',[
            'tokenString' => $tokenString
        ]);
    }

    public function processResetPassword(Request $request) {

        $token = DB::table('password_reset_tokens')->where('token', $request->token)->first();
        if ($token == null) {
            return redirect()->route('account.forgotPassword')->with('error', 'Invalid token.');
        }

        $validator = Validator::make($request->all(),[
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.resetPassword',$request->token)->withErrors($validator);
        }

        $token = DB::table('password_reset_tokens')->where('token', $request->token)->first();

        if ($token == null) {
            return redirect()->route('account.forgotPassword')->with('error', 'Invalid token.');
        }

        $user = User::where('email', $token->email)->update([
            'password' => Hash::make($request->new_password)
        ]);

       

        DB::table('password_reset_tokens')->where('email', $token->email)->delete();

        return redirect()->route('account.login')->with('success', 'Your password has been reset successfully. You can now login.');

    }
}
