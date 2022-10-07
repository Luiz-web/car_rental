<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;
use App\Repositories\CustomerRepository;

class CustomerController extends Controller
{

    public function __construct(Customer $customer) {
        $this->customer = $customer;
        $this->msgError = 'The searched customer does not exist in the database.';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customerRepository = new CustomerRepository($this->customer);

        if($request->has('filters')) {
            $filters = $request->filters;
            $customerRepository->filter($filters);
        } 

        if($request->has('attrs')) {
            $attrs = $request->attrs;
            $customerRepository->selectAttributes($attrs);
        }

        return response()->json($customerRepository->getResult(), 201);
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
     * @param  \App\Http\Requests\StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        $request->validate($this->customer->rules());

        $customer = $this->customer->create([
            'name' => $request->name,
        ]);

        return response()->json($customer, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = $this->customer->find($id);

        if($customer === null) {
            return response()->json(['error' => 'Unable to show data. '.$this->msgError]);
        }

        return response()->json($customer, 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCustomerRequest  $request
     * @param  Integer;
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = $this->customer->find($id);

        if($customer === null) {
            return response()->json(['error' => 'Unable to update data. '.$this->msgError]);
        }

        if($request->method === 'PATCH') {
            $dinamicRules = array();

            foreach($customer->rules as $input => $rule) {
                
                if(array_key_exists($input, $request->all())) {
                    $dinamicRules[$input] = $rule;
                }
            
            }
            
            $request->validate($dinamicRules);
        } else {
            $request->validate($customer->rules());
        }

        $customer->fill($request->all());
        $customer->save();

        return response()->json($customer, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = $this->customer->find($id);

        if($customer === null) {
            return response()->json(['error' => 'Unable to delete data. ' .$this->msgError]);
        }

        $customer->delete();
        
        return response()->json(['msg' => 'The customer '.$customer->name.' was successfully deleted']);
    }
}
