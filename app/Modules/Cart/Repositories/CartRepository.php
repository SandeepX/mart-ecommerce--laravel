<?php

namespace App\Modules\Cart\Repositories;

use App\Modules\Cart\Models\Cart;
use Carbon\Carbon;

class CartRepository
{

    public function findOrFailByCode($cartCode){

        return Cart::where('cart_code',$cartCode)->firstOrFail();
    }


    public function getAllCarts($user)
    {
        return $user->carts()->orderBy('updated_at','DESC')->get();
    }

    public function findOrFailCartByUserCode($cartCode,$userCode){
        return Cart::where('cart_code',$cartCode)->where('user_code',$userCode)->firstOrFail();
    }

    public function getCartRecordsFromCartCodes(array $cartCodes,$userCode){
        return Cart::whereIn('cart_code',$cartCodes)->where('user_code',$userCode)->get();
    }

    public function getDistinctWarehouseCodesFromCartCodes(array $cartCodes,$userCode){
        return Cart::select('warehouse_code')->whereIn('cart_code',$cartCodes)->where('user_code',$userCode)->distinct('warehouse_code')->get();
    }

    public function getDistinctWh(array $cartCodes,$userCode){
        return Cart::whereIn('cart_code',$cartCodes)->where('user_code',$userCode)->get();
    }

    public function getCart($productCode,$pvCode,$userCode,$wareHouseCode,$packageCode){
        return Cart::where('product_code', $productCode)
            ->where('product_variant_code',$pvCode)
            ->where('user_code',$userCode)
            ->where('warehouse_code',$wareHouseCode)
            ->where('package_code',$packageCode)
            ->first();
    }

    public function storeCart($validatedCart,$existingCartItem)
    {
        if($existingCartItem){
            $cart = $existingCartItem;
            $this->updateQuantity($existingCartItem,$validatedCart['quantity']);
        }else{
            $cart = Cart::create([
               // 'warehouse_code' => $validatedCart['warehouse_code'],
                'warehouse_code' => $validatedCart['warehouse_code'],
                'product_code' => $validatedCart['product_code'],
                'product_variant_code' => $validatedCart['product_variant_code'],
                'package_code' => $validatedCart['package_code'],
                'user_code' =>$validatedCart['user_code'],
                'initial_order_quantity' => $validatedCart['quantity'],
                'quantity' => $validatedCart['quantity']
            ]);
        }
        return $cart->fresh();

    }

//    public function updateOrCreateCart($validatedCart)
//    {
//        $cart =Cart::updateOrCreate(
//            [
//                'product_code' => $validatedCart['product_code'],
//                'product_variant_code' => $validatedCart['product_variant_code'],
//                'user_code' =>$validatedCart['user_code']
//            ],
//            [
//                'quantity' => $validatedCart['quantity'],
//            ]
//        );
//
//        return $cart->fresh();
//    }

    public function updateQuantity(Cart $cart,$quantity){

        $cart->quantity = $quantity;
        $cart->updated_at = Carbon::now();
        $cart->save();

        return $cart;
    }

    public function deleteCart($cart)
    {
        $cart->delete();
    }

    public function deleteCartByUser($user){
        $user->carts()->delete();
    }

    public function massDeleteCarts(array $cartCodes,$userCode){

       Cart::where('user_code',$userCode)->whereIn('cart_code',$cartCodes)->delete();
    }
}
