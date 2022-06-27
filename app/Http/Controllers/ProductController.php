<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the Products.
     *
     * Default pagination is used.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = Product::select()->paginate(10);
        return $this->sendResponse($products, '');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Create a new product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * NOTE: Creating categories if not exist.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required','max:255'],
            'price' => ['required','numeric'],
            'category' => ['required','max:255'],
            'description' => ['required','max:1000'],
            'avatar' => ['nullable','max:255'],
        ];

        $validator = Validator::make( $request->only(['name','price','category','description','avatar']) , $rules );
        if($validator->fails()) {

            return $this->sendError('error in creating product.', $validator->errors());

        }

        /**
         * creating categories if not exits using create
         */
        $category = $request->category;
        Category::firstOrCreate(['name'=> $category]);


        /**
         * Creating product
         */
        $data = Product::create($request->only(['name','price','category','description','avatar']));

        return $this->sendResponse($data, 'Product Created Successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $products = Product::find($id);

        if(empty($products))
        {
            return $this->sendError('Product Not found.', []);
        }

        return $this->sendResponse($products, '');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specific Product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

        $products = Product::find($id);
        if($products){
            $products->delete();
            return $this->sendResponse([], 'Product Deleted Successfully.');
        }
        return $this->sendError('This Product Not found.', []);
    }
}
