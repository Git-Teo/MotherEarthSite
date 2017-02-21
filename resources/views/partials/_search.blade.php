
<div class="input-group">
  {{ Form::text('keywords', Request::input('keywords') ?  Request::input('keywords') : "", ['placeholder' => Request::input('keywords') ?  "" : "Search", 'class' => 'form-control searchtext']) }}
  <span class="input-group-btn">
    <button class="btn btn-default" onclick="submitMainForm()" style="background: #DDD"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
  </span>
</div>
