<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Response;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function store(Request $request, $ticket)
    {


         $request->validate([
        'response' => 'required|string',
    ]);
    $ticket=Ticket::where('id',$ticket)->first();

    Response::create([
        'ticket_id' => $ticket->id,
        'user_id' => auth()->id(),
        'response' => $request->response,
    ]);



        return redirect()->back()->with('success_message', 'Responded!');
    }
}
