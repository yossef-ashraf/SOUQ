<?php

namespace App\Http\Traits;

use App\Models\Brand;
use App\Models\Promo;
use App\Models\Product;
use App\Models\Category;
use App\Models\UserWallet;
use App\Models\WalletHistory;

trait OrderTrait{
// discount 50% .....
// category-offer 50% ......
// brand-offer 50% ......
// promo 50% or 50$ ......

public function discount($price,$product_id ){
    $discount = 0 ;
    $Product = Product::where('id' , $product_id)->select('id','category_id','brand_id', 'has_discount_category', 'has_discount_brand','discount')->first();

    $discount =  $price * ( $Product->discount / 100 );
    if ($Product->has_discount_brand && isset($Product->brand_id)) {
       $has_discount_brand = ($this->has_discount_brand($price,$Product->brand_id));
        $discount = $discount + $has_discount_brand ;
    }
    if ($Product->has_discount_category && isset($Product->category_id)) {
        $has_discount_category =($this->has_discount_category($price,$Product->category_id));
        $discount = $discount + $has_discount_category ;
    }
    return $discount ;
}
public function has_discount_category($price,$category_id)
{
$category = Category::where('id' , $category_id)->select('id','offer')->first()->makeHidden(['imgurl']);
$discount = $price*($category->offer / 100);
return $discount ;
}
public function has_discount_brand($price,$brand_id){
    $Brand = Brand::where('id' , $brand_id)->select('id','offer')->first()->makeHidden(['imgurl']);
    $discount = $price*($Brand->offer / 100);
    return $discount ;
}
public function promo($price,$promo = null){
    if ($promo == null) {
        return $price ;
    }
    $promo = Promo::where('promo', $promo)->first();
    // تحقق مما إذا كان العرض الترويجي صالحًا
    if ($promo) {

        if (!is_null($promo->min_value)) {
            if ($promo->min_value > $price) {
                return $price ;
            }
        }

        if (!is_null($promo->max_value) ) {
            if ($promo->max_value < $price) {
                return $price ;
            }
        }
        if (!is_null($promo->expires_at) ) {
            if ($promo->expires_at < now()) {
                return $price ;
            }
        }

        if ($promo->type == 'percentage') {
            $price= $price - ($price * ($promo->value / 100));
            return $price ;
        }
        $price= $price - $promo->value;
        return $price ;
    }

}
public function wallet($wallet,$user_id){
    $UserWallet= UserWallet::where('user_id', $user_id)->first();
    $UserWallet->update([
        'wallet' => $UserWallet->wallet + $wallet,
    ]);
    WalletHistory::create([
        'user_wallet_id' => $UserWallet->id,
        'value' => $wallet,
    ]);
}

}
