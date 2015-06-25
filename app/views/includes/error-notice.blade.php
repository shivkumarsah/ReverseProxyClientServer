@if (Session::get('error'))
<div class="alert alert-error alert-danger">{{{ Session::get('error') }}}</div>
@endif

@if (Session::get('notice'))
<div class="alert">{{{ Session::get('notice') }}}</div>
@endif