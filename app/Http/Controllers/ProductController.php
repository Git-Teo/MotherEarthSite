<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

use App\Soap\CLFSoapClient;
use App\Http\Controllers\SoapController;
use Artisaninweb\SoapWrapper\SoapWrapper;

use App\Productsextended;
use App\Product;
use App\Basket;

class ProductController extends Controller
{
    public function getProduct($sku) {
        $sw = new SoapWrapper;
        $sc = new SoapController($sw);
        $proattr = $sc->getProductAttribute($sku);
        $product = DB::table('products')->where('sku', $sku)->first();
        $productExtended = DB::table('productsextended')->where('sku', $sku)->first();
        return view('products.show')->with('product', $product)->with('productext', $productExtended)->with('proattr', $proattr);
    }

    public function getAddToBasket(Request $request, $sku) {
        $product = DB::table('products')
                    ->join('productsextended', 'products.sku', '=', 'productsextended.sku')
                    ->select('products.*', 'productsextended.SingleUnit_Image_Url')
                    ->where('products.sku', '=', ''.$sku)
                    ->first();
        $oldBasket = Session::has('basket') ? Session::get('basket') : null;
        $basket = new Basket($oldBasket);
        $basket->add($product, $product->sku);

        $request->session()->put('basket', $basket);
        return redirect()->back()->with('success', 'true');
    }

    public function getBasket() {
        if (!Session::has('basket')) {
            return view('pages.basket')->with('products', null);
        }
        $basket = Session::get('basket');
        return view('pages.basket')->with('products', $basket->products)->with('totalPrice', $basket->totalPrice);
    }
}
