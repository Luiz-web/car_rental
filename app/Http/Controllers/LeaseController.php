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
            //It won be possible to rent an unavailable car in the cars table.
            return response()->json(['error' => 'This car is not available for lease. Please search for another car that is available']);
        } else {
            $lease->car->available = 0;
            $id_car = $request->id_car; //getting de id_car through the request to update the "cars_table".
            //updating the "cars" table, setting "available" column to false when a car is being rented 
            $car = $this->car->find($id_car);
            $car->available = 0;
            $car->save();
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
        
        $id_car = $lease->id_car; //Getting the old car's id through the lease before updated
        $car = $this->car->find($id_car);

        $lease->fill($request->all());

        if($lease->car->available === 0) {
            //It won't be possible to update to an unavailable car
            return response()->json(['error' => 'This car is not available for lease. Please search for another car that is available']);
        } else {
            // Updating the "cars" table. Making the old car available to a new lease
            $car->available = 1;
            $car->save();
            
            $lease->car->available = 0; 
            $new_id_car = $request->id_car;  //Getting the new car_id, which is available
            
            //Setting the new car unavailable after the update
            $new_car = $this->car->find($new_id_car);
            $new_car->available = 0;
            $new_car->save();
        }

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
        
        $id_car =  $lease->id_car; //Getting the id_car.
        
        $car = $this->car->find($id_car); //Searching for the car's id in the cars table.
        $car->available = 1; 
        $car->save(); //setting available again for a new lease.
        
        $lease->delete();
        
        return response()->json(['msg' => 'The leased car' .$lease->car->lisence_plate.' has been returned . It is available for a new lease']);
    }
}