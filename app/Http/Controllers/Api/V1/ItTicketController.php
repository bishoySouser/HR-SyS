<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreItTicketRequest;
use App\Http\Resources\V1\ItTicketCollection;
use App\Http\Resources\V1\ItTicketResource;
use App\Models\ItTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user(); // More concise way to get authenticated user

    $tickets = $user->itTickets()
        ->with('employee') // Eager load related employee data
        ->latest() // Order by latest creation date
        ->paginate(10); // Paginate results for efficiency

        return response()->json(new ItTicketCollection($tickets));
        // return new ItTicketCollection(ItTicket::with('employee')->where('employee_id', Auth::id())->latest(10)->limit(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItTicketRequest $request)
    {
        $employee_id = Auth::id();

        $data_to_create = [...$request->all(), 'employee_id' => $employee_id];

        $it_ticket = ItTicket::create($data_to_create);

        return response()->json(['message' => 'IT Ticket created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
