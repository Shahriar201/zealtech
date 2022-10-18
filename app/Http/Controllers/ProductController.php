<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ApiResponser;

    public function getProducts() {
        $products = DB::table('products')->get();

        return $this->set_response($products, 200, 'success', ['All Products']);

    }
}
