<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(){

        return view("frontend.order.index");
    }
    public function detail($id){

        return view("frontend.order.detail");
    }
}
