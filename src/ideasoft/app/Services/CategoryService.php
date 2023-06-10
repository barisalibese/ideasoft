<?php


namespace App\Services;


use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryService
{
    public function all(): JsonResponse
    {
        $products= Category::select('id','name')
            ->with(['products' => function ($q) {
                $q->select('products.id', 'products.name', 'products.price', 'products.stock');
            }])->get();
        return (new JsonResponse(['message'=>'success','data'=>$products],200));
    }
    public function add($request): JsonResponse
    {
        $category=(new Category())->create($request->all());
        $category = Category::select('id','name')->where('id',$category->id)
            ->with(['products' => function ($q) {
                $q->select('products.id', 'products.name', 'products.price', 'products.stock');
            }])->first();
        return (new JsonResponse(['message'=>'success','data'=>$category],200));
    }
    public function update($id,$request): JsonResponse
    {
        $data=$request->all();
        if(!$this->checkCategoryExists($id)){
            return (new JsonResponse(['message'=>'Product Doesnt Exists'],400));
        }
        $category=Category::find($id);
        $category->name=$data['name'];
        $category->save();
        return (new JsonResponse(['message'=>'success','data'=>$category],200));
    }
    public function delete($id): JsonResponse
    {
        if(!$this->checkCategoryExists($id)){
            return (new JsonResponse(['message'=>'Product Doesnt Exists'],400));
        }
        $category=Category::find($id);
        $category->products()->detach();
        $category->delete();
        return (new JsonResponse(['message'=>'success'],200));
    }
    private function checkCategoryExists($id){
        return Category::where('id',$id)->exists();
    }
}
