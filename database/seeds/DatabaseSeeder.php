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
              'description' => $description,
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

            $AllowAmbientShipping = $AllowAmbientShipping == 'true' ? 1 : 0;
            $Expiry_Date = $Expiry_Date == 'true' ? 1 : 0;
            $GSL_Product = $GSL_Product == 'true' ? 1 : 0;
            $Sell_Singles_Retail = $Sell_Singles_Retail == 'true' ? 1 : 0;

            DB::table('productsextended')->insert([
              'sku' => $product->getElementsByTagName('sku')->item(0)->textContent,
              'Long_Description' => $Long_Description,
              'Web_Description' => $Web_Description,
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

    }
}
