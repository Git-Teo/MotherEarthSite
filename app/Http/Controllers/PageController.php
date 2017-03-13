<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Product;
use App\Productsextended;
use Log;

class PageController extends Controller {

  public function getIndex(Request $request) {
    // retrieve query inputs
    $this->validate($request, array(
        'keywords' => 'regex:/^[\w\s]+$/',
        'priceFrom' => 'regex:/^\d*(\x{002E}\d{0,2})?$/',
        'priceTo' => 'regex:/^\d*(\x{002E}\d{0,2})?$/',
        'price' => 'regex:/^\d{0,2}(\x{005F}\d{0,2})?$/',
        'brands[]' => 'regex:/^[a-zA-Z\d\s]+$/',
        'categories[]' => 'regex:/^[a-zA-Z\d\s]+$/'
    ));

    $activeCategs = $request->categories;
    $activeCategs = is_array($activeCategs) ? $activeCategs : array($activeCategs);

    $brands = [];
    $activeBrands = [];

    //query database with validated request
    $categories = DB::table('productsextendeds')->select('Department')
                  // ->whereIn('brand' function($query) {
                  //     $query->select('brand')
                  // })
                  ->distinct()->get()->toArray();

    $products = DB::table('products')
                ->join('productsextendeds', 'products.sku', '=', 'productsextendeds.sku')
                ->select('products.*', 'productsextendeds.SingleUnit_Image_Url')
                ->where(function ($query) use ($request) {
                    if (count($request->categories) > 1) {
                      $query->where('productsextendeds.Department', '=', $request->categories[0]);
                      foreach (array_slice($request->categories,1) as $category) {
                        $query->orWhere('productsextendeds.Department', '=', $category);
                      }
                    } else if (count($request->categories) == 1)  {
                      $query->where('productsextendeds.Department', '=', $request->categories[0]);
                    }
                })
                ->orderBy('products.sku')->paginate(15);

    return view('pages.welcome', ['products' => $products, 'categories' => $categories,
    'activeCategs' => $activeCategs, 'brands' => $brands, 'activeBrands' => $activeBrands, 'pros' => $products->appends(Input::except('page'))]);
  }

  public function getShop() {
    return view('pages.shop');
  }

  public function getAbout() {
    return view('pages.about');
  }

  public function getContacts() {
    return view('pages.contacts');
  }

  public function getBasket() {
    return view('pages.basket');
  }

  public function getProduct($sku) {
      $product = Product::where('sku', $sku)->first();
      $productExtended = Productsextended::where('sku', $sku)->first();
      return view('products.show')->with('product', $product)->with('productext', $productExtended);
  }
}
