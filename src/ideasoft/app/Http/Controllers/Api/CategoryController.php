<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private CategoryService $service;


    public function __construct(CategoryService $categoryService)
    {
        $this->service = $categoryService;
    }

    public function index()
    {
        return $this->service->all();
    }

    public function store(StoreRequest $request)
    {
        return $this->service->add($request);
    }

    public function update($id, UpdateRequest $request)
    {
        return $this->service->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
