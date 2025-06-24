<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {

        $categories = Category::where('status',1)->withCount(['services' => function($query) {  
                                                        $query->where('status', 1);  
                                                    }])
                                                ->orderBy('name','ASC')
                                                ->take(8)
                                                ->get();

        $newCategories = Category::where('status',1)->orderBy('name','ASC')->get();

        $featuredServices = Service::where('status',1)
                            ->orderBy('created_at','DESC')
                            ->with('serviceType')
                            ->where('isFeatured',1)
                            ->take(6)->get();
        $latestServices = Service::where('status',1)
                            ->with('serviceType')
                            ->orderBy('created_at','DESC')
                            ->take(6)->get();

        return view('front.home',[
            'categories' => $categories,
            'featuredServices' => $featuredServices,
            'latestServices' => $latestServices,
            'newCategories' => $newCategories
        ]);
    }
}