<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Http\Requests\StoreLeaseRequest;
use App\Http\Requests\UpdateLeaseRequest;
use App\Repositories\LeaseRepository;
use Illuminate\Http\Request;
use App\Models\Car;


class LeaseController extends Controller
{
    public function __construct(Lease $lease, Car $car) {
        $this->lease = $lease;
        $this->car = $car;
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

        if($lease->car->available === 0) {
            return response()->json(['error' => 'This car is not available for lease. Please search for another car that is available']);
        } else {
            $lease->car->available = 0;
            
            $this->car->with('lease')->update([
                'available' => 0,
            ]);
        }

        return response()->json($lease, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lease = $this->lease->find($id);

        if($lease === null) {
            return response()->json(['error' => 'Unable to find data. The searched resource does not exists in the database']);
        }

        return response()->json($lease ,201);
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
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLeaseRequest $request, $id)
    {
        $lease = $this->lease->find($id);

        if($lease === null) {
            return response()->json(['error' => 'Unable to find data. The searched resource does not exists in the database']);
        }

        $lease->fill($request->all);

        $lease->save();

        return response()->json($lease, 200);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lease = $this->lease->find($id);
        
        if($lease === null) {
            return response()->json(['error' => 'Unable to find data. The searched resource does not exists in the database']);
        }
        
        $lease->delete();
        $car = $this->car->with('lease')->update([
            'available' => 1,
        ]);
        
        return response()->json(['msg' => 'The leased car' .$lease->car->lisence_plate.' has been returned . It is available for a new lease']);
    }
}
