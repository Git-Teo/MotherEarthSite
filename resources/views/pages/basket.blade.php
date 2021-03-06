@extends('main')

@section('title')
  MotherEarth | Basket
@endsection

@section('stylesheets')

  <link rel='stylesheet' type='text/css' href='css/parsley.css' media='all' />
  <link rel='stylesheet' type='text/css' href='css/basket.css' media='all' />

@endsection

@section('content')
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        @if ($products)
          Your Basket
          <a href="/shop" class="shop" title="Continue Shopping">Continue Shopping</a>
          <div class="basket">
            <table>
              <tr>
                <th class="col-img">
                  Items
                </th>
                <th class="col-desc">
                </th>
                <th class="col-price">
                  Price
                </th>
                <th class="col-qty">
                  Quantity
                </th>
                <th>
                  Remove
                </th>
              </tr>
              @foreach ($products as $pro)
                <tr>
                  <td>
                    @if ($pro['product']->SingleUnit_Image_Url != NULL || $pro['product']->SingleUnit_Image_Url != "")
                      <img src="{{$pro['product']->SingleUnit_Image_Url}}" alt="../images/Loading_icon.gif" class="pro-image">
                    @else
                      <img src="../images/no_image_available.jpeg" alt="../images/Loading_icon.gif" class="pro-image">
                    @endif
                  </td>
                  <td class="desc">
                    {{$pro['product']->description}}
                  </td>
                  <td class="price">
                    {{$pro['product']->msrp}}
                  </td>
                  <td>
                    <span class="sku" id="{{$pro['product']->sku}}" hidden></span>
                    <button name="less" class="less" title="Reduce Quantity"></button>
                    <input type="number" name="quantity" id="qty" class="qty" value="{{$pro['qty']}}" step="1" min="1">
                    <button name="more" class="more" title="Increase Quantity"></button>
                  </td>
                  <td>
                  </td>
                </tr>
              @endforeach
            </table>
          </div>
        @else
          Your basket is empty. </br>
          <a href="/shop" class="shop" title="Continue Shopping">Continue Shopping</a>
        @endif
      </div>
    </div>
@endsection

@section('scripts')
  <script src='js/basket.js'></script>
@endsection
