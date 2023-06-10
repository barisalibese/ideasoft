<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductService $service;


    public function __construct(ProductService $productService){
        $this->service=$productService;
    }
    public function index(){
        return $this->service->all();
    }
    public function store(StoreRequest $request){
        return $this->service->add($request);
    }
    public function update($id,UpdateRequest $request){
        return $this->service->update($id,$request);
    }
    public function destroy($id){
        return $this->service->delete($id);
    }
}
