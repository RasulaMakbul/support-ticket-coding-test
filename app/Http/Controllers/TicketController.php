<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Mail\TicketCreatedAdminMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketCloseCustomerEmail;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
                'title' => 'required|min:4|max:255',
                'description' => 'required',
        ]);

        $requestData = [
            'title' => $request->title,
            'user_id' => auth()->id(),
            'description' => $request->description,
        ];
        $ticket=Ticket::create($requestData);

        $adminEmail = 'macem3664@gmail.com';
    Mail::to($adminEmail)->send(new TicketCreatedAdminMail($ticket));
        return redirect()->back()->with('success_message',' Ticket created Successfully!');
    }




    public function close($id)
    {
        $ticket = Ticket::find($id);

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$ticket) {
            return redirect()->back()->with('error_message', 'Ticket not found.');
        }

        $ticket->status = 'closed';
        $ticket->save();

    Mail::to($ticket->user->email)->send(new TicketCloseCustomerEmail($ticket));



        return redirect()->back()->with('success_message', 'Ticket closed successfully.');
    }


}
