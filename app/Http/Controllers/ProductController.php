<?php

namespace App\Http\Controllers;

use App\Product;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use ApiResponser;

    public function getProducts() {
        $products = DB::table('products')->get();

        return $this->set_response($products, 200, 'success', ['All Products']);
    }

    public function createProduct(Request $request) {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required',
            'price' => 'required',
            'vat' => 'required',
            'discount' => 'required',
            'description' => 'required',
            'image' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        try {
            $product = new Product();
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->name = $request->name;
            $product->price = $request->price;
            $product->vat = $request->vat;
            $product->discount = $request->discount;
            $product->description = $request->description;
            $product->status = $request->status;
            $product->created_by = auth()->user()->id;

            if ($request->file('image')) {
                $file = $request->file('image');
                $fileName = date('YmdHi').$file->getClientOriginalName();
                $file->move('uploads/product_images/', $fileName);
                $product['image'] = $fileName;
            }

            $product->save();

            return $this->set_response($product, 200, 'success', ['Product created successfully']);

        } catch (\Exception $e) {
            dd($e->getMessage());

            return \redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateProduct(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:products,id',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required',
            'price' => 'required',
            'vat' => 'required',
            'discount' => 'required',
            'description' => 'required',
            'image' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        try {
            $product = Product::findOrFail($request->id);
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->name = $request->name;
            $product->price = $request->price;
            $product->vat = $request->vat;
            $product->discount = $request->discount;
            $product->description = $request->description;
            $product->status = $request->status;
            $product->created_by = auth()->user()->id;

            if ($request->file('image')) {
                $file = $request->file('image');
                @unlink(public_path('uploads/product_images/'.$product->image));
                $fileName = date('YmdHi').$file->getClientOriginalName();
                $file->move('uploads/product_images/', $fileName);
                $product['image'] = $fileName;
            }

            $product->save();

            return $this->set_response($product, 200, 'success', ['Product updated successfully']);

        } catch (\Exception $e) {
            dd($e->getMessage());

            return \redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function deleteProduct(Request $request) {
        $product = Product::findOrFail($request->id);

        if(file_exists('uploads/product_images/' . $product->image) AND ! empty($product->image)){
            unlink('uploads/product_images/' . $product->image);
        }

        $product->delete();

        return $this->set_response($product, 200, 'success', ['Product deleted successfully']);
    }
}
