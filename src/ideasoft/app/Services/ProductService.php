<?php


namespace App\Services;


use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function all(): JsonResponse
    {
        $products= Product::select('id', 'name', 'price', 'stock')
            ->with(['categories' => function ($q) {
                $q->select('categories.id', 'categories.name');
            }])->get();
        return (new JsonResponse(['message'=>'success','data'=>$products],200));
    }
    public function add($request): JsonResponse
    {
        $data = $request->all();
        DB::beginTransaction();
        $product = (new Product())->create($data);
        $response = $this->addOrUpdateProductCategory($product, $data);
        if (!$response->isSuccessful()) {
            DB::rollBack();
            return (new JsonResponse(json_decode($response->content()), 200))->send();
        }
        DB::commit();
        return (new JsonResponse(json_decode($response->content()), 200))->send();
    }

    public function update($id, $request): JsonResponse
    {
        $data = $request->all();
        if (!$this->checkProductExists($id)) {
            return (new JsonResponse(['message' => 'Product Doesnt Exists'], 400))->send();
        }
        DB::beginTransaction();

        $product = Product::find($id);
        $product->name=$data['name']??null;
        $product->stock=$data['stock']??null;
        $product->price=$data['price']??null;
        $product->save();
        if(!empty($data['categories'])){
            $response = $this->addOrUpdateProductCategory($product, $data, true);
        }
        if (!$response->isSuccessful()) {
            DB::rollBack();
            return (new JsonResponse(json_decode($response->content()), 200))->send();
        }
        DB::commit();
        return (new JsonResponse(json_decode($response->content()), 200))->send();
    }

    public function delete($id): JsonResponse
    {
        if (!$this->checkProductExists($id)) {
            return (new JsonResponse(['message' => 'Product Doesnt Exists'], 400))->send();
        }
        $product=Product::where('id', $id)->first();
        $product->categories()->detach();
        $product->delete();
        return (new JsonResponse(['message' => 'success'], 200))->send();
    }

    private function addOrUpdateProductCategory($product, $data, $update = false): JsonResponse
    {
        try {
            foreach ($data['categories'] as $category) {
                if (!$this->checkCategoryExists($category)) {
                    return (new JsonResponse(['message' => $category . ' is not exists'], 400))->send();
                }
            }
            if (!$update) {
                $product->categories()->attach($data['categories']);
            } else {
                $product->categories()->sync($data['categories']);
            }
        } catch (\Exception $e) {
            info($e->getMessage());
            return (new JsonResponse(['message' => 'System Error'], 500))->send();
        }
        $responseData = Product::select('id', 'name', 'price', 'stock')->where('id', $product->id)
            ->with(['categories' => function ($q) {
                $q->select('categories.id', 'categories.name');
            }])->first();
        return (new JsonResponse(['message' => 'success', 'data' => $responseData], 200));
    }

    private function checkProductExists($id): bool
    {
        return Product::where('id', $id)->exists();
    }

    private function checkIfProductHasCategory($product_id, $category_id)
    {
        return ProductCategory::where(['product_id' => $product_id, 'category_id', $category_id]);
    }

    private function checkCategoryExists($id)
    {
        return Category::where('id', $id)->exists();
    }
}
