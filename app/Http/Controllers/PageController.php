<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Soap\CLFSoapClient;
use App\Http\Controllers\SoapController;
use Artisaninweb\SoapWrapper\SoapWrapper;

use App\Product;
use App\Productsextended;
use Log;

use DOMDocument;

class PageController extends Controller {

  public function getIndex(Request $request) {
    // retrieve query inputs
    $this->validate($request, array(
        'keywords' => 'regex:/^[\w\s]+$/',
        'priceFrom' => 'regex:/^\d*(\x{002E}\d{0,2})?$/',
        'priceTo' => 'regex:/^\d*(\x{002E}\d{0,2})?$/',
        'price' => 'regex:/^\d{0,2}(\x{005F}\d{0,2})?$/',
        'brands[]' => 'regex:/^[a-zA-Z\d\s]+$/',
        'categories[]' => 'regex:/^[a-zA-Z\d\s]+$/',
        'sortby' => 'regex:/^[a-z]+$/'
    ));

    $activeCategs = $request->categories;
    $activeCategs = is_array($activeCategs) ? $activeCategs : array($activeCategs);

    //keywords handling
    $GLOBALS['keywords'] = explode(" ",$request->keywords);
    $GLOBALS['keywords'] = array_filter($GLOBALS['keywords']);

    //price handling
    if($request->priceFrom) {
      $request->priceFrom = (float)$request->priceFrom;
    }

    if($request->priceTo) {
      $request->priceTo = (float)$request->priceTo;
    }

    if ($request->price) {
      $request->priceFrom = (float)explode("_", $request->price)[0];
      $request->priceTo = count(explode("_", $request->price)) > 1 ? (float)explode("_", $request->price)[1] : 10000;
    }

    $brands = [];
    $activeBrands = [];

    $GLOBALS['first'] = true;
    //error outputs

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
                    //filter categories
                    if (count($request->categories) > 1) {
                      $query->where(function ($query) use ($request) {
                        $query->where('productsextendeds.Department', '=', $request->categories[0]);
                        foreach (array_slice($request->categories,1) as $category) {
                          $query->orWhere('productsextendeds.Department', '=', $category);
                        }
                      });
                    } else if (count($request->categories) == 1)  {
                      $query->where('productsextendeds.Department', '=', $request->categories[0]);
                    }

                    //filter keywords
                    if (count($GLOBALS['keywords']) >= 1) {
                        $query->where('products.description', 'LIKE', "%{$GLOBALS['keywords'][0]}%");
                      foreach (array_slice($GLOBALS['keywords'],1) as $keyword) {
                        $query->orWhere('products.description', 'LIKE', "%{$GLOBALS['keywords'][0]}%");
                      }
                    }

                })
                ->where(function ($query) use ($request) {  //filter price
                    if ($request->priceFrom) {
                        $query->whereBetween('products.msrp', [$request->priceFrom, $request->priceTo]);
                    }
                })
                ->when($request->sortby, function ($query) use ($request) {
                    if ($request->sortby == 'pricelh') {
                      return $query->orderBy('products.msrp', 'asc');
                    } else if ($request->sortby == 'pricehl') {
                      return $query->orderBy('products.msrp', 'desc');
                    } else if ($request->sortby == 'brand') {
                      return $query->orderBy('products.brand');
                    } else {
                      return $query->orderBy('products.description');
                    }
                }, function ($query) use ($request) {
                    return $query->orderBy('products.description');
                })
                ->orderBy('products.sku')->paginate(15);

    $sw = new SoapWrapper;
    $sc = new SoapController($sw);
    $updatedskusxml = $sc->getProductCodes();
    $dom=new DOMDocument();
    $dom->loadXML($updatedskusxml);
    $root=$dom->documentElement;
    $data=$root->getElementsByTagName('Code');
    $updatedskus = [];
    foreach ($data as $child) {
        array_push($updatedskus, $child->getElementsByTagName('sku')->item(0)->textContent);
    }
    $currentskus = [];
    $skus = DB::select('select sku from products');
    foreach ($skus as $sku) {
        array_push($currentskus, $sku->sku);
    }
    $newskus = array_diff($updatedskus, $currentskus);

    // DB::table('products')
    //             ->join('productsextendeds', 'products.sku', '=', 'productsextendeds.sku')
    //             ->select('products.*', 'productsextendeds.SingleUnit_Image_Url')
    //             ->where(function ($query) use ($request) {
    //                 //filter categories
    //                 if (count($request->categories) > 1) {
    //                   $query->where(function ($query) use ($request) {
    //                     $query->where('productsextendeds.Department', '=', $request->categories[0]);
    //                     foreach (array_slice($request->categories,1) as $category) {
    //                       $query->orWhere('productsextendeds.Department', '=', $category);
    //                     }
    //                   });
    //                 } else if (count($request->categories) == 1)  {
    //                   $query->where('productsextendeds.Department', '=', $request->categories[0]);
    //                 }
    //
    //                 //filter keywords
    //                 if (count($GLOBALS['keywords']) >= 1) {
    //                     $query->where('products.description', 'LIKE', "%{$GLOBALS['keywords'][0]}%");
    //                   foreach (array_slice($GLOBALS['keywords'],1) as $keyword) {
    //                     $query->orWhere('products.description', 'LIKE', "%{$GLOBALS['keywords'][0]}%");
    //                   }
    //                 }
    //
    //             })
    //             ->where(function ($query) use ($request) {  //filter price
    //                 if ($request->priceFrom) {
    //                     $query->whereBetween('products.msrp', [$request->priceFrom, $request->priceTo]);
    //                 }
    //             })
    //             ->toSql();

    return view('pages.welcome', ['test' => $test, 'products' => $products, 'categories' => $categories,
    'activeCategs' => $activeCategs, 'brands' => $brands, 'activeBrands' => $activeBrands, 'pros' => $products->appends(Input::except('page'))]);
  }

  public function getShop(Request $request) {
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

    //keywords handling
    $GLOBALS['keywords'] = explode(" ",$request->keywords);
    $GLOBALS['keywords'] = array_filter($GLOBALS['keywords']);

    //price handling
    if($request->priceFrom) {
      $request->priceFrom = (float)$request->priceFrom;
    }

    if($request->priceTo) {
      $request->priceTo = (float)$request->priceTo;
    }

    if ($request->price) {
      $request->priceFrom = (float)explode("_", $request->price)[0];
      $request->priceTo = count(explode("_", $request->price)) > 1 ? (float)explode("_", $request->price)[1] : 10000;
    }

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
                    //filter categories
                    if (count($request->categories) > 1) {
                      $query->where(function ($query) use ($request) {
                        $query->where('productsextendeds.Department', '=', $request->categories[0]);
                        foreach (array_slice($request->categories,1) as $category) {
                          $query->orWhere('productsextendeds.Department', '=', $category);
                        }
                      });
                    } else if (count($request->categories) == 1)  {
                      $query->where('productsextendeds.Department', '=', $request->categories[0]);
                    }

                    //filter keywords
                    if (count($GLOBALS['keywords']) >= 1) {
                        $query->where('products.description', 'LIKE', "%{$GLOBALS['keywords'][0]}%");
                      foreach (array_slice($GLOBALS['keywords'],1) as $keyword) {
                        $query->orWhere('products.description', 'LIKE', "%{$GLOBALS['keywords'][0]}%");
                      }
                    }

                })
                ->where(function ($query) use ($request) {  //filter price
                    if ($request->priceFrom) {
                        $query->whereBetween('products.msrp', [$request->priceFrom, $request->priceTo]);
                    }
                })
                ->when($request->sortby, function ($query) use ($request) {
                    if ($request->sortby == 'pricelh') {
                      return $query->orderBy('products.msrp', 'asc');
                    } else if ($request->sortby == 'pricehl') {
                      return $query->orderBy('products.msrp', 'desc');
                    } else if ($request->sortby == 'brand') {
                      return $query->orderBy('products.brand');
                    } else {
                      return $query->orderBy('products.description');
                    }
                }, function ($query) use ($request) {
                    return $query->orderBy('products.description');
                })
                ->orderBy('products.sku')->paginate(15);

    return view('pages.shop', ['products' => $products, 'categories' => $categories,
    'activeCategs' => $activeCategs, 'brands' => $brands, 'activeBrands' => $activeBrands, 'pros' => $products->appends(Input::except('page'))]);
  }

  public function getAbout() {
    return view('pages.about');
  }

  public function getContacts() {
    return view('pages.contacts');
  }
}
