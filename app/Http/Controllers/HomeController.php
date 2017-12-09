<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class HomeController extends Controller{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('home');
    }
	public function rateList(){
		$coin_rate_file = base_path() . '/ext/coin_rate.json';
		$coin_rate_json = file_get_contents($coin_rate_file);
		$coin_rate_array = json_decode($coin_rate_json);
		$this->data['coin_rate_array'] = $coin_rate_array;

		return view('rate_list', $this->data);
	}
}
