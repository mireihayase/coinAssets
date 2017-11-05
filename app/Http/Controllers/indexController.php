<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class indexController extends Controller{

	public function archive(){
		return view('archive');
	}

	public function index(){
		return view('index');
	}

	public function dashboard(){
		return view('dashboard');
	}

	public function detail(){
		return view('detail');
	}
}
