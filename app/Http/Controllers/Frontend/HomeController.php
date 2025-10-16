<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\AdminNotification;
use App\Models\About;
use App\Models\Category;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Slider;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Models\UserMessages;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    //
    public function index(){

        return view('frontend.home.index');
    }



}
