@extends('front.loginfront')
@section('title','Password Changed')
@section('Content')
<div class="container text-center">
    <h3>{{ $message }}</h3>
    <p>Redirecting to home in 3 seconds...</p>
</div>
<script>
    setTimeout(function(){
        window.location.href = "{{ $redirectUrl }}";
    },3000);
</script>
@endsection
