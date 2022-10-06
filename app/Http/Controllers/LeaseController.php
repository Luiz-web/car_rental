<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Http\Requests\StoreLeaseRequest;
use App\Http\Requests\UpdateLeaseRequest;
use App\Repositories\LeaseRepository;
use Illuminate\Http\Request;


class LeaseController extends Controller
{
    public function __construct(Lease $lease) {
        $this->lease = $lease;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $leaseRepository = new LeaseRepository($this->lease);

        if($request->has('customer_attrs')) {
            $customer_attrs = 'customer:id,'.$request->customer_attrs;
            $leaseRepository->selectCustomerAttributes($customer_attrs);
        } else {
            $leaseRepository->selectCustomerAttributes('customer');
        }

        if($request->has('relational_attrs')) {
            $relational_attrs = 'car:id,'.$request->relational_attrs;
            $leaseRepository->selectRelationalAttributes($relational_attrs);
        }

        if($request->has('filters')) {
            $filters = $request->filters;
            $leaseRepository->filter($filters);
        }

        if($request->has('attrs')) {
            $attrs = $request->attrs;
            $leaseRepository->selectAttributes($attrs);
        }

        return response()->json($leaseRepository->getResult(), 201);
        
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

        $lease = $this->lease->create([
            'id_customer' => $request->id_customer,
            'id_car' =>  $request->id_car,
            'start_date' => $request->start_date,
            'end_date_expected' => $request->end_date_expected,
            'end_date_accomplished' => $request->end_date_accomplished,
            'daily_value' => $request->daily_value,
            'initial_km' => $request->initial_km,
            'final_km' => $request->final_km,
        ]);

        return response()->json($lease, 200);
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
