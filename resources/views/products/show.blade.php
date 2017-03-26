@extends('main')

@section('title')
  MotherEarth | {{$product->description}}
@endsection

@section('stylesheets')
  <link rel='stylesheet' type='text/css' href='../css/product.css' media='all' />
@endsection

@section('content')
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="pro-content row">
        <div class="col-md-4">
          @if ($productext->SingleUnit_Image_Url != NULL || $productext->SingleUnit_Image_Url != "")
            <img src="{{$productext->SingleUnit_Image_Url}}" alt="../images/Loading_icon.gif" class="pro-image">
          @else
            <img src="../images/no_image_available.jpeg" alt="../images/Loading_icon.gif" class="pro-image">
          @endif
        </div>
        <div class="col-md-8">
          <div class="pro-head">
            <span class="title">
              {{$product->description}}</br>
            </span>
            <span class="brand">
              {{$product->brand}}
            </span>
            <div class="desc">
              {{str_replace(".",".".PHP_EOL, $productext->Long_Description)}}
            </div>
          </div>
          <div class="separator"></div>
          <div class="pro-add">
            <span class="price">£{{$product->msrp}}</span></br>
            <!--masterpackquantity:
            {{$product->masterpackquantity}}</br>
            minimumorderquantity:
            {{$product->minimumorderquantity}}</br> -->
            <span class="text">Quantity</span><button name="less" class="less" title="Reduce Quantity"></button>
            <input type="number" name="quantity" id="qty" class="qty" value="{{$product->minimumorderquantity}}" step="{{$product->minimumorderquantity}}" min="{{$product->minimumorderquantity}}">
            <button name="more" class="more" title="Increase Quantity"></button>
            <button class="add-btn" title="Add to Basket">Add</button></br>
            <span class="help">Choose how many you would like and then click add.</span>
          </div>
          <div class="separator"></div>
          <div class="pro-details">
            <div class="title">Information</div>
            <div class="subtitle">Size:</div>
            {{$product->size}}</br>
            <div class="subtitle">Weight:</div>
            {{$product->weight}}</br>
            <div class="subtitle">Department:</div>
            {{$productext->Department}}</br>
            <div class="subtitle">Storage:</div>
            {{$productext->Storage}}</br>
            <div class="subtitle">Guaranteed Life:</div>
            {{$productext->Guaranteed_Shelf_Life}}</br>
            <div class="subtitle">Average Life:</div>
            {{$productext->Shelf_Life}}</br>
          </div>
          <div class="separator"></div>
          <div class="pro-attributes">
            <div class="title">Dietary and Lifestyle </div>
            @foreach ($proattr->childNodes as $attr)
              @if ($attr->nodeValue == "False" || $attr->nodeValue == "True")
                <div class="att-{{$attr->nodeValue}}"></div>
                <span class="text-{{$attr->nodeValue}}">{{str_replace(array("#text", "_"), array("", " "), $attr->nodeName)}}</span></br>
              @endif
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')

  <script src="../js/productpage.js"></script>

@endsection
