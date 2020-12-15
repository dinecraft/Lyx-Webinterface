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
            <p>{!! __('Now lets create the SuperAdmin Account:') !!}</p><br>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
             <form action="{{ url('/install/setup/4/post') }}" method="POST">
                @csrf
                <p>{{ __('SuperAdmin Name') }}:<input name="name" class="form-control" value="" placeholder="{{ __('for example') }} mysql.domain.ending"></p><br>
                <p>{{ __('Email') }}:<input name="email" class="form-control" value="" placeholder="{{ __('for example') }} mysql.domain.ending"></p><br>
                <p>{{ __('Password') }}:<input name="password" class="form-control" value="" placeholder="{{ __('for example') }} databaseName"></p><br>
                <p>{{ __('Repat Password') }}:<input name="repeat" class="form-control" value="" placeholder="{{ __('for example') }} myUser"></p><br>
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