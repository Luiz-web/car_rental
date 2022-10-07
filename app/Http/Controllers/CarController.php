<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use Illuminate\Http\Request;
use App\Repositories\CarRepository;

class CarController extends Controller
{
   

    public function __construct(Car $car) {
        $this->car = $car;
        $this->msgError = 'The searched car does not exist in the database';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carRepository = new CarRepository($this->car);

        if($request->has('relational_attrs')) {
            $relational_attrs = 'carModel:id,'.$request->relational_attrs;
            $carRepository->SelectRelationalAttributes($relational_attrs);
        } else {
            $carRepository->SelectRelationalAttributes('carModel');
        }

        if($request->has('filters')) {
            $filters = $request->filters;
            $carRepository->filter($filters);
        }

        if($request->has('attrs')) {
            $attrs = $request->attrs;
            $carRepository->selectAttributes($attrs);
        }

        return response()->json($carRepository->getResult(), 200);
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
     * @param  \App\Http\Requests\StoreCarRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCarRequest $request)
    {
        $request->validate($this->car->rules());

        $car = $this->car->create([
            'id_car_model' => $request->id_car_model,
            'lisence_plate' => $request->lisence_plate,
            'available' => $request->available,
            'km' => $request->km,
        ]);

        return response()->json($car, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $car = $this->car->with('carModel')->find($id);

        if($car === null) {
            return response()->json(['error' => 'Unable to show data. '.$this->msgError], 404);
        }

        return response()->json($car, 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarRequest  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarRequest $request, $id)
    {
        $car = $this->car->find($id);

        if($car === null) {
            return response()->json(['error' => 'Unable to update data. '.$this->msgError], 404);
        }

        if($request->method() === 'PATCH') {
            $dinamicRules = array();

            foreach($car->rules() as $input => $rule) {

                if(array_key_exists($input, $request->all())) {
                    $dinamicRules[$input] = $rule;
                }
            }
           
            $request->validate($dinamicRules);
        } else {
            $request->validate($car->rules());
        }

        $car->fill($request->all());
        $car->save();

        return response()->json($car, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $car = $this->car->find($id);

        if($car === null) {
            return response()->json(['error' => 'Unable do delete data. '.$this->msgError], 404);
        }

        $car->delete();
        return response()->json(['msg' => 'The car with the lisence_plate '.$car->lisence_plate.' was successfully deleted']);
    }
}
