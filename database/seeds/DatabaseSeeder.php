<?php

use Illuminate\Database\Seeder;
use App\Soap\CLFSoapClient;
use App\Http\Controllers\SoapController;
use Artisaninweb\SoapWrapper\SoapWrapper;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sw = new SoapWrapper;
        $sc = new SoapController($sw);

        $products = $sc->getProducts();
        foreach ($products as $product) {

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

        $products = $sc->getProductsExtended();
        foreach ($products as $product) {
            $AllowAmbientShipping = $product->getElementsByTagName('AllowAmbientShipping')->item(0)->textContent;
            $Expiry_Date = $product->getElementsByTagName('Expiry_Date')->item(0)->textContent;
            $GSL_Product = $product->getElementsByTagName('GSL_Product')->item(0)->textContent;
            $Sell_Singles_Retail = $product->getElementsByTagName('Sell_Singles_Retail')->item(0)->textContent;

            $AllowAmbientShipping = $AllowAmbientShipping == 'True' ? 1 : 0;
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

        $products = $sc->getProductAttributes();
        foreach ($products as $product) {
            print($product->getElementsByTagName('sku')->item(0)->textContent);
            DB::table('productattributes')->insert([
              'sku' => $product->getElementsByTagName('sku')->item(0)->textContent,
              'Dairy Free' => $product->getElementsByTagName('Dairy_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Gluten Free' => $product->getElementsByTagName('Gluten_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Organic' => $product->getElementsByTagName('Organic')->item(0)->textContent == 'True' ? 1 : 0,
              'Raw' => $product->getElementsByTagName('Raw')->item(0)->textContent == 'True' ? 1 : 0,
              'Vegan' => $product->getElementsByTagName('Vegan')->item(0)->textContent == 'True' ? 1 : 0,
              'Vegetarian' => $product->getElementsByTagName('Vegetarian')->item(0)->textContent == 'True' ? 1 : 0,
              'Wheat Free' => $product->getElementsByTagName('Wheat_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Sugar Free' => $product->getElementsByTagName('Sugar_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Fair Trade' => $product->getElementsByTagName('Fair_Trade')->item(0)->textContent == 'True' ? 1 : 0,
              'Produce Of GB' => $product->getElementsByTagName('Produce_Of_GB')->item(0)->textContent == 'True' ? 1 : 0,
              'Nut Free' => $product->getElementsByTagName('Nut_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Egg Free' => $product->getElementsByTagName('Egg_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Celery Free' => $product->getElementsByTagName('Celery_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Lupin Free' => $product->getElementsByTagName('Lupin_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Milk Free' => $product->getElementsByTagName('Milk_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Mustard Free' => $product->getElementsByTagName('Mustard_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Sesame Seed Free' => $product->getElementsByTagName('Sesame_Seed_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Soybean Free' => $product->getElementsByTagName('Soybean_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Sulphur Dioxide Free' => $product->getElementsByTagName('Sulphur_Dioxide_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Maize Free' => $product->getElementsByTagName('Maize_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Citric Acid Free' => $product->getElementsByTagName('Citric_Acid_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Aluminium Free' => $product->getElementsByTagName('Aluminium_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Paraben Free' => $product->getElementsByTagName('Paraben_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Crustacean Free' => $product->getElementsByTagName('Crustacean_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Mollusc Free' => $product->getElementsByTagName('Mollusc_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Fish Free' => $product->getElementsByTagName('Fish_Free')->item(0)->textContent == 'True' ? 1 : 0,
              'Peanut Free' => $product->getElementsByTagName('Peanut_Free')->item(0)->textContent == 'True' ? 1 : 0,
            ]);
        }
    }
}
