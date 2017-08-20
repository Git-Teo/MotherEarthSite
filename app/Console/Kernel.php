<?php

namespace App\Console;

use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Soap\CLFSoapClient;
use App\Http\Controllers\SoapController;
use Artisaninweb\SoapWrapper\SoapWrapper;
use DOMDocument;
use SimpleXMLElement;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {

            $sw = new SoapWrapper;
            $sc = new SoapController($sw);
            $dom=new DOMDocument();

            $updatedskusxml = $sc->getProductCodes();
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

            $xml = new SimpleXMLElement('<ProductCodes/>');

            foreach ($newskus as $sku) {
              $xml->addChild('Code', $sku);
            }

            $dom = new DOMDocument();
            $products = $sc->getProducts($xml->saveXML());
            foreach ($products as $product){
              DB::table('products')->insert([
                'sku' => $product->getElementsByTagName('sku')->item(0)->textContent,
                'brand' => $product->getElementsByTagName('brand')->item(0)->textContent,
                'description' => $product->getElementsByTagName('description')->item(0)->textContent,
                'size' => $product->getElementsByTagName('size')->item(0)->textContent,
                'weight' => $product->getElementsByTagName('weight')->item(0)->textContent,
                'barcode' => $product->getElementsByTagName('barcode')->item(0)->textContent,
                'masterpackquantity' => $product->getElementsByTagName('masterpackquantity')->item(0)->textContent,
                'taxcode' => $product->getElementsByTagName('taxcode')->item(0)->textContent,
                'price' => $product->getElementsByTagName('price')->item(0)->textContent,
                'msrp' => $product->getElementsByTagName('msrp')->item(0)->textContent,
                'minimumorderquantity' => $product->getElementsByTagName('minimumorderquantity')->item(0)->textContent,
              ]);
            }

            $products = $sc->getProductsExtended($xml->saveXML());
            foreach ($products as $product) {
                $AllowAmbientShipping = $product->getElementsByTagName('AllowAmbientShipping')->item(0)->textContent;
                $Expiry_Date = $product->getElementsByTagName('Expiry_Date')->item(0)->textContent;
                $GSL_Product = $product->getElementsByTagName('GSL_Product')->item(0)->textContent;
                $Sell_Singles_Retail = $product->getElementsByTagName('Sell_Singles_Retail')->item(0)->textContent;

                $AllowAmbientShipping = $AllowAmbientShipping == 'true' ? 1 : 0;
                $Expiry_Date = $Expiry_Date == 'true' ? 1 : 0;
                $GSL_Product = $GSL_Product == 'true' ? 1 : 0;
                $Sell_Singles_Retail = $Sell_Singles_Retail == 'true' ? 1 : 0;

                DB::table('productsextended')->insert([
                  'sku' => $product->getElementsByTagName('sku')->item(0)->textContent,
                  'Long_Description' => $product->getElementsByTagName('Long_Description')->item(0)->textContent,
                  'Web_Description' => $product->getElementsByTagName('Web_Description')->item(0)->textContent,
                  'Department' => $product->getElementsByTagName('Department')->item(0)->textContent,
                  'Storage' => $product->getElementsByTagName('Storage')->item(0)->textContent,
                  'AllowAmbientShipping' => $AllowAmbientShipping,
                  'Expiry_Date' => $Expiry_Date,
                  'Shelf_Life' => $product->getElementsByTagName('Shelf_Life')->item(0)->textContent,
                  'Guaranteed_Shelf_Life' => $product->getElementsByTagName('Guaranteed_Shelf_Life')->item(0)->textContent,
                  'GSL_Product' => $GSL_Product,
                  'Sell_Singles_Retail' => $Sell_Singles_Retail,
                  'Outer_Gross_Weight' => $product->getElementsByTagName('Outer_Gross_Weight')->item(0)->textContent,
                  'Outer_Barcode' => $product->getElementsByTagName('Outer_Barcode')->item(0)->textContent,
                  'SingleUnit_Image_Url' => $product->getElementsByTagName('SingleUnit_Image_Url')->item(0)->textContent,
                  'Outer_Image_Url' => $product->getElementsByTagName('Outer_Image_Url')->item(0)->textContent,
                  //'Distance_Selling_PDF' => $product->getElementsByTagName('Distance_selling_PDF')->item(0)->textContent,
                ]);
            }

            $products = $sc->getProductAttributes($xml->saveXML());
            foreach ($products as $product) {
                DB::table('productattributes')->insert([
                  'sku' => $product->getElementsByTagName('sku')->item(0)->textContent,
                  'Dairy Free' => $product->getElementsByTagName('Dairy Free')->item(0)->textContent,
                  'Gluten Free' => $product->getElementsByTagName('Gluten Free')->item(0)->textContent,
                  'Organic' => $product->getElementsByTagName('Organic')->item(0)->textContent,
                  'Raw' => $product->getElementsByTagName('Raw')->item(0)->textContent,
                  'Vegan' => $product->getElementsByTagName('Vegan')->item(0)->textContent,
                  'Vegetarian' => $product->getElementsByTagName('Vegetarian')->item(0)->textContent,
                  'Wheat Free' => $product->getElementsByTagName('Wheat Free')->item(0)->textContent,
                  'Sugar Free' => $product->getElementsByTagName('Sugar_Free')->item(0)->textContent,
                  'Fair Trade' => $product->getElementsByTagName('Fair Trade')->item(0)->textContent,
                  'Produce Of GB' => $product->getElementsByTagName('Produce_Of_GB')->item(0)->textContent,
                  'Nut Free' => $product->getElementsByTagName('Nut Free')->item(0)->textContent,
                  'Egg Free' => $product->getElementsByTagName('Egg Free')->item(0)->textContent,
                  'Celery Free' => $product->getElementsByTagName('Celery Free')->item(0)->textContent,
                  'Lupin Free' => $product->getElementsByTagName('Lupin Free')->item(0)->textContent,
                  'Milk Free' => $product->getElementsByTagName('Milk_Free')->item(0)->textContent,
                  'Mustard Free' => $product->getElementsByTagName('Mustard Free')->item(0)->textContent,
                  'Sesame Seed Free' => $product->getElementsByTagName('Sesame Seed Free')->item(0)->textContent,
                  'Soybean Free' => $product->getElementsByTagName('Soybean Free')->item(0)->textContent,
                  'Sulphur Dioxide Free' => $product->getElementsByTagName('Sulphur Dioxide Free')->item(0)->textContent,
                  'Maize Free' => $product->getElementsByTagName('Maize_Free')->item(0)->textContent,
                  'Citric Acid Free' => $product->getElementsByTagName('Citric Acid Free')->item(0)->textContent,
                  'Aluminium Free' => $product->getElementsByTagName('Aluminium Free')->item(0)->textContent,
                  'Paraben Free' => $product->getElementsByTagName('Paraben_Free')->item(0)->textContent,
                  'Crustacean Free' => $product->getElementsByTagName('Crustacean Free')->item(0)->textContent,
                  'Mollusc Free' => $product->getElementsByTagName('Mollusc_Free')->item(0)->textContent,
                  'Fish Free' => $product->getElementsByTagName('Fish Free')->item(0)->textContent,
                  'Peanut Free' => $product->getElementsByTagName('Peanut Free')->item(0)->textContent,
                ]);
            }

        })->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
