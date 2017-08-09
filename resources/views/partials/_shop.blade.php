
{!! Form::open(array('route' => 'index', 'method' => 'get', 'class'=> 'mainForm')) !!}
<div class="row">
  <div class="col-md-3 col-md-offset-2" style ="border-left:1px solid #DDD">
    Showing page {{$products->currentPage()}} of {{$products->lastPage()}}
    <p>
      {{($products->perPage() * $products->currentPage()) - $products->perPage() +1}}-{{($products->perPage() * $products->currentPage())}} of {{$products->total()}} Results
    </p>
    <div class="dropdown">
      Sort By:
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        @if (Request::input('sortby'))
          @if (Request::input('sortby') == 'pricelh')
            Price: Low to High
          @elseif (Request::input('sortby') == 'pricehl')
            Price: High to Low
          @elseif (Request::input('sortby') == 'relevance')
            Relevance
          @elseif (Request::input('sortby') == 'brand')
            Brand
          @endif
        @else
          Relevance
        @endif
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li>{{ Form::radio('sortby', 'relevance', Request::input('sortby') ? Request::input('sortby') == 'relevance' ? true : false : true, array('onChange' => 'submitFormNoRange()')) }}Relevance</li>
        <li>{{ Form::radio('sortby', 'pricelh', Request::input('sortby') == 'pricelh' ? true : false, array('onChange' => 'submitFormNoRange()')) }}Price: Low to High</li>
        <li>{{ Form::radio('sortby', 'pricehl', Request::input('sortby') == 'pricehl' ? true : false, array('onChange' => 'submitFormNoRange()')) }}Price: High to Low</li>
        <li>{{ Form::radio('sortby', 'brand', Request::input('sortby') == 'brand' ? true : false, array('onChange' => 'submitFormNoRange()')) }}Brand</li>
      </ul>
    </div>
  </div>
  <div class="col-md-6">
    @include('partials._search')
  </div>
</div>
<hr>
<div class="row">
  <div class="col-md-2 filters">

    @if (!empty($categories))
    <div class="form-group" id="CategoriesGrp">
      {{ Form::label('categories[]', 'Categories') }}
      @foreach ($categories as $category)
      <div class="checkbox">
        <label>
          {{ Form::checkbox('categories[]', $category->Department, in_array($category->Department, $activeCategs) ? true : false, array('onChange' => 'submitMainForm()')) }}
          {{ $category->Department }}
        </label>
      </div>
      @endforeach
    </div>
    <hr>
    @endif

    @if (!empty($brands))
    <div class="form-group" id="FeaturedBrands">
      {{ Form::label('featuredBrands', 'Featured Brands') }}
      @foreach ($brands as $brand)
      <div class="checkbox">
        <label>
          {{ Form::checkbox('brands[]', $brand->brand, in_array($brand->brand, $activeBrands) ? true : false, array('onChange' => 'submitMainForm()')) }}
          {{ $brand->brand }}
        </label>
      </div>
      @endforeach
    </div>
    <hr>
    @endif

    <div class="form-group" id="PriceRangeGrp">
      {{ Form::label('price', 'Price') }}
      <div class="radio">
        <label>
          {{ Form::radio('price', '0_10', Request::input('price') == '0_10' ? true : false, array('onChange' => 'submitFormNoRange()')) }}
          under £10
        </label>
      </div>

      <div class="radio">
        <label>
          {{ Form::radio('price', '10_25', Request::input('price') == '10_25' ? true : false, array('onChange' => 'submitFormNoRange()')) }}
          £10-£25
        </label>
      </div>

      <div class="radio">
        <label>
          {{ Form::radio('price', '25_50', Request::input('price') == '25_50' ? true : false, array('onChange' => 'submitFormNoRange()')) }}
          £25-£50
        </label>
      </div>

      <div class="radio">
        <label>
          {{ Form::radio('price', '50', Request::input('price') == '50' ? true : false, array('onChange' => 'submitFormNoRange()')) }}
          Over £50
        </label>
      </div>

      <div class="input-group">
        <table>
          <tr>
            <th>{{ Form::label('priceFrom', 'From £', array('style' => 'opacity: 0.7')) }}</th>
            <th>{{ Form::label('priceTo', 'To £', array('style' => 'opacity: 0.7')) }}</th>
          </tr>
          <tr>
            <th>{{ Form::text('priceFrom', Request::input('priceFrom') ? Request::input('priceFrom') : '', array('class' => 'form-control', 'id' => 'PriceFrom')) }}</th>
            <th>{{ Form::text('priceTo', Request::input('priceTo') ? Request::input('priceTo') : '', array('class' => 'form-control', 'id' => 'PriceTo')) }}</th>
            <th><button class="btn btn-default" id="priceSubmit"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></button></th>
          </tr>
        </table>
      </div>
    </div>
    <hr>
  </div>
  <div class="col-xs-9">
    <!-- Products -->
    @foreach ($products->chunk(3) as $productschunk)
      <div class="row">
        @foreach ($productschunk as $product)
          <a href="/products/{{$product->sku}}">
            <div class="col-xs-6 col-sm-6 col-md-4">
              <div class="thumbnail">
                <img src="{{$product->SingleUnit_Image_Url != "" ? $product->SingleUnit_Image_Url : "images/no_image_available.jpeg"}}" alt="Sorry could not find image" style="max-height: 250px">
                <div class="caption">
                  <h4>{{$product->description}}</h4>
                  <p>by {{$product->brand}}</p>
                  <div class="clearfix">
                    <div class="pull-left price">£{{$product->msrp}}</div>
                    <p><a href={{ route('addToBasket', ['sku' => $product->sku]) }} class="btn btn-default pull-right" role="button">Add to Basket</a></p>
                  </div>
                </div>
              </div>
            </div>
          </a>
        @endforeach
      </div>
    @endforeach

    <div class= "text-center">
      {!! $products->links() !!}
    </div>

  </div>
</div>

{!! Form::close() !!}
