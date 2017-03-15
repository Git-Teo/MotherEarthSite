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
            <span class="pro-title">
              {{$product->description}}</br>
            </span>
            <span class="pro-brand">
              {{$product->brand}}
            </span>
            <div class="pro-descr">
              {{str_replace(".",".".PHP_EOL, $productext->Long_Description)}}
            </div>
          </div>
          <div class="pro-add">
            £{{$product->msrp}}</br>
            masterpackquantity:
            {{$product->masterpackquantity}}</br>
            minimumorderquantity:
            {{$product->minimumorderquantity}}</br>
            <input type="number" name="quantity" class="add-qty" value="{{$product->minimumorderquantity}}" step="{{$product->minimumorderquantity}}">
            <input type="button" class="add-btn">
          </div>
          <div class="pro-details">
            Size:
            {{$product->size}}</br>
            Weight:
            {{$product->weight}}</br>
            Department:
            {{$productext->Department}}</br>
            Storage:
            {{$productext->Storage}}</br>
            Guaranteed Life:
            {{$productext->Guaranteed_Shelf_Life}}</br>
            Average Life:
            {{$productext->Shelf_Life}}</br>
          </div>
          <div class="pro-attributes">
            @foreach ($proattr->childNodes as $attr)
              {{str_replace(array("#text", "_"), array("", " "), $attr->nodeName)}}
              {{$attr->nodeValue}}</br>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
