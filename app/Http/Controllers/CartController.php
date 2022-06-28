<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a Cart of a guest or auth user.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {


        if( Auth::user())
        {
            $cart = Cart::where('user_id', Auth::user()->id)->get();

        }else{

            $rules = [
                'session_id' => ['required','max:255']
            ];
            $validator = Validator::make( $request->only(['session_id']) , $rules );

            if($validator->fails()) {
                return $this->sendError('Error in fetching cart.', $validator->errors());
            }
            $cart = Cart::where('session_id', $request->session_id)->get();
        }


        if(count($cart) == 0)
        {

            return $this->sendResponse($cart, 'Cart is Empty.');
        }
        return $this->sendResponse($cart, '');
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
     * Add specific item to cart for guest and auth user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'session_id' => ['required','max:255'],

            'product_id' => ['required','numeric'],
            'qty' => ['required','numeric'],
        ];
        $validator = Validator::make( $request->only(['session_id','user_id','product_id','qty']) , $rules );

        if($validator->fails()) {
            return $this->sendError('Error in creating cart.', $validator->errors());
        }

        $user_id = 0; // default 0 for gust use, we can update this later while logged in using session id.

        // if user is authenticated set user id for cart items.
        if(Auth::check())
        {
            $user_id = Auth::user()->id;
        }

        $data = Cart::create([
            'session_id' => $request->session_id,
            'user_id' => $user_id,
            'product_id' => $request->product_id,
            'qty' => $request->qty
        ]);

        return $this->sendResponse($data, 'Product added to cart.');
    }

    /**
     * Display the Cart items by cartID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * Update the specified Cart Items by CartId.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $rules = [

            'qty' => ['required','numeric'],
        ];
        $validator = Validator::make([

            'qty' => $request->qty
        ] , $rules );
        if($validator->fails()) {
            return $this->sendError('Error in updating cart.', $validator->errors());
        }


        $data = Cart::find($id);
        if(empty($data))
        {
            return $this->sendError('Error in fetching cart Item.', []);
        }
        $data->qty = $request->qty;
        $data->save();

        return  $this->sendResponse($data, 'Product Qty update.');
    }

    /**
     * Remove the specified cart item from cart.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $cart = Cart::find($id);
        if($cart){
            $cart->delete();
            return $this->sendResponse([], 'Cart Item Deleted Successfully.');
        }
        return $this->sendError('This Cart Not found.', []);
    }
}
