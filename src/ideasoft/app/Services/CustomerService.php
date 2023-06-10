<?php


namespace App\Services;


use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerService
{
    public function all(){
        $data=Customer::select('id','name','revenue','created_at as since')->get();
        if (empty($data)){
            return (new JsonResponse('User Not Found',400));
        }
        return (new JsonResponse(['status'=>'success','data'=>$data],200));
    }
    public function store(Request $request){
        $data=$request->all();
        if (Customer::where('email',$data['email'])->exists()){
            return (new JsonResponse('email exists',400));
        }
        $customer = new Customer();
        $customer->name=$data['name'];
        $customer->email=$data['email'];
        $customer->password=bcrypt($data['password']);
        if (!$customer->save()){
            return (new JsonResponse('user could not registered',400));
        }
        return (new JsonResponse($data,200));
    }

    public function login(Request $request){
        $credentials = array_merge($request->only(['email', 'password']));
        if(Auth::attempt($credentials)){
            $customer=Auth::user();
            $tokenResult=$customer->createToken('User Access Token');
            $token=$tokenResult->accessToken;
            $token->expires_at=now()->addMonth();
            $token->save();
            $body = [
                'token_type'   => 'Bearer',
                'access_token' => $tokenResult->plainTextToken,
                'expires_at'   => Carbon::parse(
                    $token->expires_at
                )->toDateTimeString(),
            ];
            return (new JsonResponse($body,200))->send();
        }
        return (new JsonResponse(['errors'=>['Email or Password is Wrong!']],400))->send();
    }
}
