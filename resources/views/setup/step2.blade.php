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
        <div class="txt-marked d-flex justify-content-center">
            <p>{!! __('Thanks for using our Solution :) If you need Support - Contact us. <br> With clicking the next button, I ensure that I respect and accept all Terms and conditions, wich I can find in the official Website.') !!}</p>
        </div>
        <div style="margin-top: 3rem;" class="d-flex justify-content-center">
             <a href="{{ url('/install/setup/3') }}" class="btn p-button w-50">{{ __('Accept and Confirm >') }} ></a>
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