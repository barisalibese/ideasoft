<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\LoginRequest;
use App\Http\Requests\Customer\StoreRequest;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
class CustomerController extends Controller
{
    private CustomerService $service;
    public function __construct(CustomerService $customerService){
        $this->service=$customerService;
    }
    public function index(){
        return $this->service->all();
    }
    public function register(StoreRequest $request): JsonResponse
    {
        return $this->service->store($request);
    }
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->service->login($request);
    }
}
