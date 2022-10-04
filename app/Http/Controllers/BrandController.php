<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Requests\DeleteBrandRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Repositories\BrandRepository;

class BrandController extends Controller
{

    public function __construct(Brand $brand) {
        $this->brand = $brand;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $brandRepository = new BrandRepository($this->brand);
       
        if($request->has('relational_attrs')) {
            $relational_attrs = 'carModels:id,'.$request->relational_attrs;
            $brandRepository->selectRelationalAttributes($relational_attrs);
        }  else {
            $brandRepository->selectRelationalAttributes('carModels');
        }

        if($request->has('filters')) {
            $filters = $request->filters;
           $brandRepository->filter($filters);
        }

        if($request->has('attrs')) {
            $attrs = $request->attrs;
            $brandRepository ->selectAttributes($attrs);
        }

        return response()->json($brandRepository->getResult(),200);
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
     * @param  \App\Http\Requests\StoreBrandRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBrandRequest $request)
    {
        $request->validate($this->brand->rules());
        
        $image = $request->file('image');
        $image_urn = $image->store('imgs/brands', 'public');

        $brand = $this->brand->create([
            'name' => $request->name,
            'image' => $image_urn,
        ]);

        return response()->json($brand, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = $this->brand->with('carModels')->find($id);
        if($brand === null) {
            return response()->json(['error' => 'searched resource does not exist in the database '], 404);
        }

        return response()->json($brand, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBrandRequest  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBrandRequest $request, $id)
    {
        $brand = $this->brand->find($id);

        if($brand === null) {
            return response()->json(['error' => 'searched resource does not exist in the database '], 404);
        }

        if($request->method() === 'PATCH') {
            $dinamicRules = array();

            foreach($brand->rules() as $input => $rule) {
                
                if(array_key_exists($input, $request->all())) {
                    $dinamicRules[$input] = $rule;
                }
            }
            
            $request->validate($dinamicRules);
        
        } else {
            $request->validate($brand->rules());
        }

        if($request->file('image')) {
            Storage::disk('public')->delete($brand->image);
        }

        $image = $request->file('image');
        $image_urn = $image->store('imgs/brands', 'public');

        $brand->fill($request->all());
        $brand->image = $image_urn;

        $brand->save();

        return response()->json($brand, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $brand = $this->brand->find($id);
        
        if($brand === null) {
            return response()->json(['error' => 'searched resource does not exist in the database '], 404);
        }

        if($request->file('image')) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->delete();
        return response()->json(['msg' => "The brand $brand->name has been successfully deleted"], 200);



    }
}
