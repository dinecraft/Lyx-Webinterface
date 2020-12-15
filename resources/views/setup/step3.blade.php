@extends('layouts.minimal')

@section('content')
<div class="container-fluid">
    <h1><b class="fb">|</b> SETUP</h1>
</div>
<div class="container-fluid d-flex justify-content-center" style="margin-top: 13rem;">
    <div class="container license-agr bg-light">
        <div class="w-100 justify-content-center d-flex flex-wrap" style="margin-bottom: 5rem; margin-top: 3rem;">
            <h2><u>W</u>elcome to the Panel !</h2>
        </div>
        <div class="txt-marked justify-content-center">
            <p>{!! __('In the next Step please add your Database Credintials. (MySQL Database prefered)') !!}</p><br>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
             <form action="{{ url('/install/setup/3/post') }}" method="POST">
                @csrf
                <p>{{ __('Host') }}:<input name="mysql_host" class="form-control" value="127.0.0.1" placeholder="{{ __('for example') }} mysql.domain.ending"></p><br>
                <p>{{ __('Database') }}:<input name="mysql_db" class="form-control" value="" placeholder="{{ __('for example') }} databaseName"></p><br>
                <p>{{ __('Username') }}:<input name="mysql_user" class="form-control" value="root" placeholder="{{ __('for example') }} myUser"></p><br>
                <p>{{ __('Password') }}:<input name="mysql_pw" class="form-control" value="" placeholder="{{ __('for example') }} ****" type="password"></p><br>
                <p>{{ __('Port') }}:<input name="mysql_port" class="form-control" value="3306" placeholder="{{ __('for example') }} 3306"></p><br>
                <div style="margin-top: 3rem;" class="d-flex justify-content-center">
                    <button type="submit" onclick="this.disabled=true;" class="btn p-button w-50">{{ __('Next') }} ></a>
               </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/selectize.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/selectize.min.css') }}" />
<script>
$(document).ready(function() {
  $('.select').selectize({});
});
</script>
@endsection