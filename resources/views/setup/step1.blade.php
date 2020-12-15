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
        <div class="divselect d-flex justify-content-center">
            <form action="{{ url('/install/setup/post') }}" method="post">
                @csrf
                <select class="select" name="lang">
                    <option value="" selected>Please Select Language</option>
                    <option value="en" >English (default)</option>
                    <option value="de" >Deutsch (german)</option>
                </select>
                <div style="margin-top: 3rem;" class="d-flex justify-content-center">
                    <button type="submit" class="btn p-button w-50">Install ></a>
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