@if(count($errors)>0)
<div class="login_info_section">
    @foreach($errors->all() as $error)
    <h3>{{$error}}</h3>
    @endforeach
</div><!--login_info_section-->
@elseif(Session::has('error'))
<div class="login_info_section">
    <h3>{{ session('error') }}</h3>
</div><!--login_info_section-->
@endif