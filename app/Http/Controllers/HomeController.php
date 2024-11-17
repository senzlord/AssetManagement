<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perangkat;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $Hardware = Perangkat::where('TYPE', 'Hardware')->count();
        $NonHardware = Perangkat::where('TYPE', 'Non-Hardware')->count();
        $SFP = Perangkat::where('TYPE', 'SFP')->count();
        
        return view('home', [
            'Hardware' => $Hardware,
            'NonHardware' => $NonHardware,
            'SFP' => $SFP,
        ]);
    }
}
