<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use App\Http\Requests\StoreCarModelRequest;
use App\Http\Requests\UpdateCarModelRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Repositories\CarModelRepository;

class CarModelController extends Controller
{

    public function __construct(CarModel $carModel) {
        $this->carModel = $carModel;
        $this->msgError = 'The searched model does not exist in the database';
    }
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carModelRepository = new CarModelRepository($this->carModel);

        if($request->has('relational_attrs')) {
            $relational_attrs = 'brand:id,'.$request->relational_attrs;
            $carModelRepository->SelectRelationalAttributes($relational_attrs);
        } else {
            $carModelRepository->selectRelationalAttributes('brand');
        }

        if($request->has('filters')) {
            $filters = $request->filters;
            $carModelRepository->filter($filters);
        }
        
        if($request->has('attrs')) {
            $attrs = $request->attrs;
            $carModelRepository->selectAttributes($attrs);
        } 
        
        return response()->json($carModelRepository->getResult(), 200);
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
            return response()->json(['error' => 'Unable to show data. ' .$this->msgError]);
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
            return response()->json(['error' => 'Unable to update data. '.$this->msgError]);
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
            return response()->json(['error' => 'Unable to delete data. '.$this->msgError], 404);
        }

        if($request->file('image')) {
            Storage::disk('public')->delete($brand->image);
        }

        $carModel->delete();
        return response()->json(['msg' => "The model $carModel->name has been successfully deleted"], 201);
    }
}
