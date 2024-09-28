<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
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


        if (auth()->user()->isAdmin()) {
            $tickets = Ticket::with('user')->get();
        } else {
            $tickets = Ticket::where('user_id', auth()->id())->get();
        }

        return view('admin.dashboard',compact('tickets'));
    }
}
