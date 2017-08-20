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
use SimpleXMLElement;

class PageController extends Controller {

  public function getIndex(Request $request) {
    // retrieve query inputs
    $this->validate($request, array(
        'keywords' => 'regex:/^[\w\s]+$/',
        'priceFrom' => 'regex:/^\d*(\x{002E}\d{0,2})?$/',
        'priceTo' => 'regex:/^\d*(\x{002E}\d{0,2})?$/',
        'price' => 'regex:/^\d{0,2}(\x{005F}\d{0,2})?$/',
        'brands[]' => 'regex:/^[a-zA-Z\d\s]+$/',
        'categories[]' => 'regex:/^[a-zA-Z\d\s]+$/|max:50',
        'attr[]' => 'regex:/^[a-zA-Z\d\s]+$/|max:25',
        'allergens[]' => 'regex:/^[a-zA-Z\d\s]+$/|max:25',
        'sortby' => 'regex:/^[a-z]+$/'
    ));

    $sw = new SoapWrapper;
    $sc = new SoapController($sw);

    $activeCategs = $request->categories;
    $activeCategs = is_array($activeCategs) ? $activeCategs : array($activeCategs);

    $activeAttributes = $request->attr;
    $activeAttributes = is_array($activeAttributes) ? $activeAttributes : array($activeAttributes);

    $activeAllergens = $request->allergens;
    $activeAllergens = is_array($activeAllergens) ? $activeAllergens : array($activeAllergens);

    //keywords handling
    $GLOBALS['keywords'] = explode(" ", $request->keywords);
    $GLOBALS['keywords'] = array_filter($GLOBALS['keywords']);

    //price handling
    if ($request->priceFrom) {
      $request->priceFrom = (float)$request->priceFrom;
    } else {
      $request->priceFrom = (float)0;
    }

    if ($request->priceTo) {
      $request->priceTo = (float)$request->priceTo;
    } else {
      $request->priceTo = (float)100000000;
    }

    if ($request->price) {
      $request->priceFrom = (float)explode("_", $request->price)[0];
      $request->priceTo = count(explode("_", $request->price)) > 1 ? (float)explode("_", $request->price)[1] : 10000;
    }

    $brands = [];
    $activeBrands = [];
    //error outputs

    //query database with validated request
    $categories = DB::table('productsextended')->select('Department')
                  // ->whereIn('brand' function($query) {
                  //     $query->select('brand')
                  // })
                  ->distinct()->get()->toArray();

    $attributes = ['Dairy Free', 'Gluten Free', 'Organic', 'Raw', 'Vegan', 'Vegetarian', 'Wheat Free', 'Sugar Free', 'Fair Trade', 'Produce of GB'];
    $allergens = ['Nut Free', 'Egg Free', 'Celery Free', 'Lupin Free', 'Milk Free', 'Mustard Free', 'Sesame Seed Free', 'Soy Bean Free', 'Sulphur Dioxide Free', 'Maize Free',
                  'Citric Acid Free', 'Aluminium Free', 'Paraben Free', 'Crustacean Free', 'Mollusc Free', 'Fish Free', 'Peanut Free', 'No Added Sugar', 'Certified Low FODMAP'];

    $test = $request->attr;

    $products = DB::table('products')
                ->join('productsextended', 'products.sku', '=', 'productsextended.sku')
                ->join('productattributes', 'products.sku', '=', 'productattributes.sku')
                ->select('products.*', 'productsextended.SingleUnit_Image_Url')
                ->where(function ($query) use ($request) {
                    //filter categories
                    if (count($request->categories) > 1) {
                      $query->where(function ($query) use ($request) {
                        $query->where('productsextended.Department', '=', $request->categories[0]);
                        foreach (array_slice($request->categories,1) as $category) {
                          $query->orWhere('productsextended.Department', '=', $category);
                        }
                      });
                    } else if (count($request->categories) == 1)  {
                      $query->where('productsextended.Department', '=', $request->categories[0]);
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
                    if (count($request->attr) >= 1) {
                      $query->where("productattributes.{$request->attr[0]}", '=', '1');
                      foreach (array_slice($request->attr,1) as $a) {
                        $query->where("productattributes.{$a}", '=', '1');
                      }
                    }
                    if (count($request->allergens) >= 1) {
                      $query->where("productattributes.{$request->allergens[0]}", '=', '1');
                      foreach (array_slice($request->allergens,1) as $a) {
                        $query->where("productattributes.{$a}", '=', '1');
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

    // $test1 = DB::table('products')
    //                   ->join('productsextended', 'products.sku', '=', 'productsextended.sku')
    //                   ->select('products.*', 'productsextended.SingleUnit_Image_Url')
    //                   ->where(function ($query) use ($request) {
    //                       //filter categories
    //                       if (count($request->categories) > 1) {
    //                         $query->where(function ($query) use ($request) {
    //                           $query->where('productsextended.Department', '=', $request->categories[0]);
    //                           foreach (array_slice($request->categories,1) as $category) {
    //                             $query->orWhere('productsextended.Department', '=', $category);
    //                           }
    //                         });
    //                       } else if (count($request->categories) == 1)  {
    //                         $query->where('productsextended.Department', '=', $request->categories[0]);
    //                       }
    //
    //                       //filter keywords
    //                       if (count($GLOBALS['keywords']) >= 1) {
    //                           $query->where('products.description', 'LIKE', "%{$GLOBALS['keywords'][0]}%");
    //                         foreach (array_slice($GLOBALS['keywords'],1) as $keyword) {
    //                           $query->orWhere('products.description', 'LIKE', "%{$GLOBALS['keywords'][0]}%");
    //                         }
    //                       }
    //
    //                   })
    //                   ->where(function ($query) use ($request) {  //filter price
    //                       if ($request->priceFrom) {
    //                           $query->whereBetween('products.msrp', [$request->priceFrom, $request->priceTo]);
    //                       }
    //                   })
    //                   ->toSql();

    return view('pages.welcome', ['test' => $test, 'products' => $products, 'categories' => $categories,
    'activeCategs' => $activeCategs, 'brands' => $brands, 'activeBrands' => $activeBrands, 'attributes' => $attributes,
    'activeAttributes' => $activeAttributes, 'allergens' => $allergens, 'activeAllergens' => $activeAllergens, 'pros' => $products->appends(Input::except('page'))]);
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
    $categories = DB::table('productsextended')->select('Department')
                  // ->whereIn('brand' function($query) {
                  //     $query->select('brand')
                  // })
                  ->distinct()->get()->toArray();

    $products = DB::table('products')
                ->join('productsextended', 'products.sku', '=', 'productsextended.sku')
                ->select('products.*', 'productsextended.SingleUnit_Image_Url')
                ->where(function ($query) use ($request) {
                    //filter categories
                    if (count($request->categories) > 1) {
                      $query->where(function ($query) use ($request) {
                        $query->where('productsextended.Department', '=', $request->categories[0]);
                        foreach (array_slice($request->categories,1) as $category) {
                          $query->orWhere('productsextended.Department', '=', $category);
                        }
                      });
                    } else if (count($request->categories) == 1)  {
                      $query->where('productsextended.Department', '=', $request->categories[0]);
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
