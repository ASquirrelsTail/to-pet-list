@extends('layouts.app')

@section('content')
    <h1>To Pet List</h1>
    <p>Keep track of all the animals you need to pet with your own personal To Pet List!</p>
    <p>Do you ever find yourself losing track of all the animals you plan on petting/stroking/scratching/belly-rubbing?</p>
    <p>Well, we've got the answer! With To Pet List you can create a list of all the animals you want to pet, then mark them off as you pet them. It really is that simple!</p>
    <p>Say goodbye to wondering if you ever got around to petting a sealion! Sign up now!</p>
    @guest
        @if (Route::has('register'))
            <a href="{{ route('register') }}">{{ __('Register') }}</a>
        @endif
    @endguest
@endsection
