<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateProductFormRequest;

use App\Entities\Repository\ProductRepository;

class ProductController extends Controller
{
    private $product;

    public function __construct(ProductRepository $product)
    {
        $this->product = $product;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $product = $this->product->getResults($search);
        
        return response()->json($product);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateProductFormRequest $request)
    {
        $product = $this->product->store($request);
        if ($product === 'None') {
            return response()->json(['error' => 'It is impossible to create'], 403);
        }elseif ($product === 'ImageFailure') {
            return response()->json(['error' => 'Fail_Upload'], 500);
        }else {
            return response()->json($product, 201);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->product->show($id);
        if ($product === 'None') {
            return response()->json(['error' => 'Not Found'], 404);
        }else {
            return $product;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateProductFormRequest $request, $id)
    {
        $product = $this->product->update($request, $id);
        if($product === 'None')
        {
            return response()->json(['error' => 'Not Found'], 404);
        }elseif ($product === 'ImageFailure') {
            return response()->json(['error' => 'Fail_Upload'], 500);
        }else{
            return response()->json($product);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->product->destroy($id);
        if ($product === 'None') {
            return response()->json(['error' => 'Not Found'], 404);
        }else {
            return response()->json(['success'=> true], 204);
        }
    }
}
