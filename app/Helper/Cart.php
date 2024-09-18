<?php

namespace App\helper;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;

class Cart
{
    public static function getCount()
    {
        if ($user = auth()->user()) {
            return CartItem::whereUserId($user->id)->sum('quantity');
        }
    }
    public static function getCartItems()
    {
        if ($user = auth()->user()) {
            return CartItem::whereUserId($user->id)->get()->map(fn(CartItem $item) => ['product_id', $item->product_id, 'quantity' => $item->quantity]);
        }
    }
    public static function getCookieCartItems()
    {
        return json_decode(request()->cookie('cart_items', '[]'), true);
    }
    public static function setCookieCartItems()
    {
        Cookie::queue('cart_items', fn(int $carry, array $item) => $carry + $item['quantity'], 0);
    }
    public static function saveCookieCartItens()
    {
        $user = auth()->user();
        $userCartItems = CartItem::where(['user_id' => $user->id])->get()->keyBy('product_id');
        $savedCartItems  = [];
        foreach (self::getCookieCartItems() as $cartItem) {
            if (isset($userCartItems[$cartItem['product_id']])) {
                $userCartItems[$cartItem['product_id']]->update(['quantity' => $cartItem['quantity']]);
                continue;
            }
            $savedCartItems[]  = [
                'user_id' => $user->id,
                'product_id' => $cartItem['product_id'],
                'quantity' => $cartItem['quantity'],
            ];
        }
        if (!empty($savedCartItems)) {
            CartItem::insert($savedCartItems);
        }
    }

    public static function moveCartIntoDb()
    {
        $request = request();
        $cartItems = self::getCookieCartItems();
        $newCartItems = [];
        foreach ($cartItems as $cartItem) {
            $existingCartItem = CartItem::where([
                'user_id' => $request->user()->id,
                'product_id' => $cartItem['product_id'],
            ])->first();
            if (!$existingCartItem) {
                $newCartItems[] = [
                    'user_id' => $request->user()->id,
                    'product_id' => $cartItem['product_id'],
                    'quantity' => $cartItem['quantity'],
                ];
            }
        }
        if (!empty($newCartItems)) {
            CartItem::insert($newCartItems);
        }
    }
    // public static function getProductsAndCartItems(){
    //     $cartItems = self::getCartItems();
    //     $ids = Arr::pluck($cartItems, 'product_id');
    //     $products = Product::whereIn('id', $ids)->with('product_images')->get();
    //     $cartItems = Arr::keyBy($cartItems, 'product_id');

    //     return [$products, $cartItems];
    // }
    public static function getProductsAndCartItems()
{
    // Ensure getCartItems() returns an array or collection, fallback to empty array if null
    $cartItems = self::getCartItems() ?? [];

    // Extract product IDs safely, ensuring $cartItems is an array
    $ids = Arr::pluck($cartItems, 'product_id');

    // Fetch products by these IDs
    $products = Product::whereIn('id', $ids)->with('product_images')->get();

    // Re-key the cart items array by product_id to make it easier to access
    $cartItems = Arr::keyBy($cartItems, 'product_id');

    return [$products, $cartItems];
}

}
