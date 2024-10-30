<?php

namespace App\Http\Controllers\User;

use App\helper\Cart;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function view(){
        
    }
    public function store(Request $request, $product){
        
        Log::info('Cart count: ' . Cart::getCount());
        $product = Product::where('id', $product)->first();
        $quantity = $request->post('quantity', 1);
        $user = $request->user();

        if($user){
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if($cartItem){
                $cartItem->increment('quantity');
            }else{
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]);
            }
            $cartItems = Cart::getCookieCartItems();
            $isProductExist = false;
            foreach($cartItems as $item){
                if($item['product_id'] == $product->id){
                    $item['quantity'] += $quantity;
                    $isProductExist = true;
                    break;
                }
            }
            if(!$isProductExist){
                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ];
            }
            Cart::setCookieCartItems($cartItems);
        }
        return redirect()->back()->with('success', 'Item added to cart Successfully');

    }
    public function update(Request $request, Product $product){
        $quantity = $request->integer(('quantity'));
        $user = $request->user();
        if($user){
            CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->update(['quantity' => $quantity]);
        }else{
            $cartItems = Cart::getCookieCartItems();
            foreach($cartItems as $item){
                if($item['product_id'] == $product->id){
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);
        }
        return redirect()->back();
    }
    public function destroy(Request $request, Product $product){
            $user = $request->user();
            if ($user) {
                # code..
                CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first()?->delete();
                 if(CartItem::count() <= 0){
                    return redirect()->route('home')->with('info', 'Your Cart Is Empty'); 
                 }else{
                    return redirect()->back()->with('success', 'Item removed successfully'); 
                 }
            }else{
                $cartItems = Cart::getCookieCartItems();
                foreach($cartItems as $i => &$item){
                    if($item['product_id'] == $product->id) {
                        array_splice($cartItems, $i, 1);
                        break;
                    }
                }
                Cart::setCookieCartItems($cartItems);
                if(CartItem::count() <= 0){
                    return redirect()->route('home')->with('info', 'Your Cart Is Empty'); 
                 }else{
                    return redirect()->back()->with('success', 'Item removed successfully'); 
                 }
            }
    }
}
