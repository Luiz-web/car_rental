<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use App\Http\Requests\StoreCarModelRequest;
use App\Http\Requests\UpdateCarModelRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class CarModelController extends Controller
{

    public function __construct(CarModel $carModel) {
        $this->carModel = $carModel;
    }
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carModels = array();

        if($request->has('brand_attr')) {
            $brand_attr = $request->brand_attr;
            $carModels = $this->carModel->with('brand:id,'.$brand_attr);
        } else {
            $carModels = $this->carModel->with('brand');
        }

        if($request->has('filter')) {
            $filters = explode(';', $request->filter);
            foreach($filters as $key => $condition) {
                $c = explode(':', $condition);
                $carModels = $carModels->where($c[0], $c[1], $c[2]);
            }
            
        }
        
        if($request->has('attr')) {
            $attr = $request->attr;
            $carModels = $carModels->selectRaw($attr)->get();
        } else {
            $carModels = $carModels->get();
        }
        return response()->json($carModels, 200);
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
     * @param  \App\Http\Requests\StoreCarModelRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCarModelRequest $request)
    {
        $request->validate($this->carModel->rules());

        $image = $request->file('image');
        $image_urn = $image->store('imgs/models', 'public');

        $carModel = $this->carModel->create([
            'id_brand' => $request->id_brand,
            'name' => $request->name,
            'image' => $image_urn,
            'number_doors' => $request->number_doors,
            'seats' => $request->seats,
            'abs' => $request->abs,
            'air_bag' => $request->air_bag,
        ]);

        return response()->json($carModel, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carModel = $this->carModel->with('brand')->find($id);

        if($carModel === null) {
            return response()->json(['error' => 'The searched resource does not exist in the database']);
        }

        return response()->json($carModel, 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CarModel  $carModel
     * @return \Illuminate\Http\Response
     */
    public function edit(CarModel $carModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarModelRequest  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarModelRequest $request, $id)
    {
        $carModel = $this->carModel->find($id);

        if($carModel === null) {
            return response()->json(['error' => 'The searched resource does not exist in the database']);
        }

        if($request->method() === 'PATCH') {
            $dinamicRules = array();

            foreach($carModel->rules() as $input => $rule) {
                
                if(array_key_exists($input, $request->all())) {
                    $dinamicRules[$input] = $rule;
                }
            }

            $request->validate($dinamicRules);
        
        } else {
            $request->validate($carModel->rules());
        }  

        $carModel->fill($request->all());

        if($request->file('image')) {
            Storage::disk('public')->delete($carModel->image);
            $image = $request->file('image');
            $image_urn = $image->store('imgs', 'public');
            $carModel->image = $image_urn;
        }

        $carModel->save();
        
        return response()->json($carModel, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $carModel = $this->carModel->find($id);

        
        if($carModel === null) {
            return response()->json(['error' => 'The searched resource does not exist in the database'], 404);
        }

        if($request->file('image')) {
            Storage::disk('public')->delete($brand->image);
        }

        $carModel->delete();
        return response()->json(['msg' => "The model $carModel->name has been successfully deleted"], 201);
    }
}
