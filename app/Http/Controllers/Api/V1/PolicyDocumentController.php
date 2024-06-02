<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PolicyDocumentCollection;
use App\Models\PolicyDocument;
use Illuminate\Http\Request;

class PolicyDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new PolicyDocumentCollection(PolicyDocument::status(1)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
