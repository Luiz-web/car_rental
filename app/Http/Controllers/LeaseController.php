<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Http\Requests\StoreLeaseRequest;
use App\Http\Requests\UpdateLeaseRequest;

class LeaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLeaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeaseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lease  $lease
     * @return \Illuminate\Http\Response
     */
    public function show(Lease $lease)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lease  $lease
     * @return \Illuminate\Http\Response
     */
    public function edit(Lease $lease)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLeaseRequest  $request
     * @param  \App\Models\Lease  $lease
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLeaseRequest $request, Lease $lease)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lease  $lease
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lease $lease)
    {
        //
    }
}
