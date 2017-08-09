<?php

namespace App;

class Basket
{
  //
    public $products;
    public $totalQty = 0;
    public $totalPrice = 0;

    public function __construct($oldBasket) {
        if ($oldBasket) {
            $this->products = $oldBasket->products;
            $this->totalQty = $oldBasket->totalQty;
            $this->totalPrice = $oldBasket->totalPrice;
        }
    }

    public function add($product, $sku, $qty = 1) {
        $storedProducts = ['qty' => 0, 'price' => $product->msrp, 'product' => $product];
        if ($this->products) {
            if (array_key_exists($sku, $this->products)) {
                $storedProducts = $this->products[$sku];
            }
        }
        $storedProducts['qty'] += $qty;
        $storedProducts['price'] = $product->msrp * $storedProducts['qty'];
        $this->products[$sku] = $storedProducts;
        $this->totalQty += $qty;
        $this->totalPrice += $product->msrp * $qty;

    }
}
