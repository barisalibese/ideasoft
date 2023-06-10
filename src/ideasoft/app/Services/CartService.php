<?php


namespace App\Services;


use App\Services\Discount\DiscountService;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomerCart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CartService
{
    private $cart;
    public function __construct($delete=false){
        if($delete){
            $customer=auth('sanctum')->user();
            $this->cart=$customer->cart()->where('status','ordering')->first();
            if(empty($this->cart)){
                return (new JsonResponse('user cart is not exists',400))->send();
            }
        }
        else{
            $customer=auth('sanctum')->user();
            $this->cart=$customer->cart()->where('status','ordering')->first();
            if(empty($this->cart)){
                $this->cart=CustomerCart::create(['customer_id'=>$customer->id,'status'=>'ordering']);
            }
        }
    }

    public function getCart(): JsonResponse
    {
        $cartData=CustomerCart::where('id',$this->cart->id)->with(['cartItems'=>function($q){
            $q->select('id','product_id','quantity','unit_price','total_price');
        }])->first();
        return (new JsonResponse(['message'=>'success','data'=>$cartData],200))->send();
    }

    public function createOrUpdateCart($request): JsonResponse
    {
        $data=$request->all();
        DB::beginTransaction();
        foreach ($data['products'] as $product){
            if(!$this->checkProductAndStockExists($product['id'],$product['quantity'])){
                DB::rollBack();
                return (new JsonResponse($product['id'].' is not exists or no stock',400))->send();
            }
            else if ($this->checkItemCreated($product['id'])){;
               $this->updateItemFromCart($product);
            }
            else {
               $this->addItemToCart($product);
            }
        }
        $this->cart->total_price=$this->cart->cartItems()->sum('total_price');
        $this->cart->save();
        DB::commit();
        (new DiscountService())->build($this->cart);
       /* $cartData=CustomerCart::where('id',$this->cart->id)->with(['cartItems'=>function($q){
            $q->select('id','product_id','quantity','price');
        }])->first();*/
        return (new JsonResponse(['message'=>'success','data'=>$this->getResponseData($this->cart)],200))->send();
    }

    public function deleteItemFromCart($request): JsonResponse
    {
        $data=$request->all();
        if (isset($data['all']) && $data['all']){
            $this->cart->cartItems()->delete();
            $this->cart->delete();
        }
        foreach ($data['products'] as $product){
            if(!$this->checkItemCreated($product['id'])){
                DB::rollBack();
                return (new JsonResponse($product['id'].' is not exists',400))->send();
            }
            else if (isset($product['all']) && $product['all']){
               $this->deleteCartItems($product['id']);
            }
            else if (isset($product['quantity'])){
                $this->decrementCartItems($product['id'],$product['quantity']);
            }
            $this->cart->total_price=$this->cart->cartItems()->sum('total_price');
            $this->cart->save();
            DB::commit();
            (new DiscountService())->build($this->cart);
           /* $cartData=CustomerCart::where('id',$this->cart->id)->with(['cartItems'=>function($q){
                $q->select('id','product_id','quantity','price');
            }])->first();*/
        }
        return (new JsonResponse(['message'=>'success','data'=>$this->getResponseData($this->cart)],200))->send();
    }

    private function decrementCartItems($productId,$quantity){
        $item=$this->cart->cartItems()->where('product_id',$productId)->first();
        if($item->quantity-$quantity <=0){
            $item->delete();
            if(!$this->cart->cartItems()->exists()){
                $this->cart->delete();
            }
        }
        else{
            $item->quantity=$item->quantity-$quantity;
            $item->unit_price=$this->getProductPrice($productId);
            $item->total_price=$item->quantity*$item->unit_price;
            $item->save();
        }
    }

    private function deleteCartItems($id){
        $this->cart->cartItems()->where('product_id',$id)->delete();
        if(!$this->cart->cartItems()->exists()){
            $this->cart->delete();
        }
        else{
            $this->cart->total_price=$this->cart->cartItems()->sum('total_price');
            $this->cart->save();
        }
    }

    private function addItemToCart($product){
        $this->cart->cartItems()->create([
            'product_id'=>$product['id'],
            'quantity'=>$product['quantity'],
            'total_price'=>$this->getProductPrice($product['id'])*$product['quantity'],
            'unit_price'=>$this->getProductPrice($product['id'])
        ]);
    }

    private function updateItemFromCart($product){
        $item=$this->cart->cartItems()->where('product_id',$product['id'])->first();
        $item->quantity=$item->quantity+$product['quantity'];
        $item->total_price=$this->getProductPrice($product['id'])*$item->quantity;
        $item->save();
    }

    private function checkProductAndStockExists($id,$quantity){
        $cartItem=$this->cart->cartItems()->where('product_id',$id)->first();
        if(!empty($cartItem)){
            $quantity=$cartItem->quantity+$quantity;
        }
        return Product::where('id',$id)->where('stock','>',$quantity)->exists();
    }

    private function checkItemCreated($id){
        return $this->cart->cartItems()->where('product_id',$id)->exists();
    }

    private function getProductPrice($id){
        return Product::find($id)->price;
    }

    private function getResponseData($cart){
        $cartData['cart']=CustomerCart::select('id','customer_id as customerId','total_price as total')->where('id',$cart->id)->first();
        $cartData['cart']['items']=$cartData['cart']->cartItems()->selectRaw('product_id as productId, quantity, unit_price , total_price')->get();
        return $cartData;
    }
}
