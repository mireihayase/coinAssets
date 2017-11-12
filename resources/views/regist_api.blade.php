<!DOCTYPE html>
<html>

@include('include/head')

  <body>
  @include('include/header')

  @include('include/sidebar')

    <div class="main-contents">
      <div class="main-contents__body">
        <ul class="breadcrumbs">
          <li class="breadcrumbs__list"><a href="#">Home</a></li>
          <li class="breadcrumbs__list">API 登録</li>
        </ul>
        <h1 class="page-header"><i class="fa fa-file-text"></i><span>API 登録</span></h1>
      @if(!empty($message))
        <h1 class="page-header"></i><span style="color: red;">{{$message}}</span></h1>
      @endif
        <form method="post" action="" class="panel">
          <div class="panel__body">
            <dl class="data-list"><dt class="data-list__title">API Key</dt>
              <dd class="data-list__body">
                <input type="text" name="api_key" placeholder="{{$api_key}}" value="{{$api_key}}" class="input--large" size="60">
              </dd>
            </dl>
            <dl class="data-list"><dt class="data-list__title">API Secret</dt>
              <dd class="data-list__body">
                <input type="password" name="api_secret" placeholder="{{$api_secret}}" value="{{$api_secret}}"  class="input--large"size="60">
              </dd>
            </dl>
          </div>
          <div class="panel__foot">
            <ul class="list list-btn align--right">
              <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
              <input type="hidden" name="exchange_id" value="{{$exchange_id}}">
              {{--<li><button type="button" class="btn btn--dark with-icon"><i class="fa fa-reply"></i>Back</button></li>--}}
              <li><button type="submit" class="btn btn--success with-icon"><i class="fa fa-check"></i>Submit</button></li>
            </ul>
          </div>
        </form>
      </div>
    </div>
    {{--<script src="./bower_components/jquery/dist/jquery.min.js"></script>--}}
    {{--<script src="./bower_components/adminize/js/adminize.min.js"></script>--}}
  </body>

</html>