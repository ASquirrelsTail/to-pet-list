@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center">
        <div class="col-lg-4 d-none d-lg-flex">
            <img src="/images/paw.svg" alt="Paw logo" class="img-fluid w-100">
        </div>
        <div class="col-md-8 col-lg-6">
            <h1 class="text-center">A list of animals to pet!</h1>
            <p>Keep track of all the animals you need to pet with your own personal To Pet List!</p>
            <p>Do you ever find yourself losing track of all the animals you plan on petting/stroking/scratching/belly-rubbing?</p>
            <p>Well, we've got the answer! With To Pet List you can create a list of all the animals you want to pet, then mark them off as you pet them. It really is that simple!</p>
        </div>
        @if (sizeof($lists) > 0)
        <div class="col-md-8 col-lg-6">
            <div class="card mt-4">
                <div class="card-header">
                    <h3>Check out some lists:</h3>
                </div>
                <div class="card-body">
                    <ul>
                        @foreach ($lists as $listref)
                        <li><a href="{{ route('lists.show', $listref->list)}}">{{ $listref->list->name }} by {{ $listref->list->user->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
        <div class="col-md-8 col-lg-6 text-center mt-5">
            <h2>Say goodbye to wondering if you ever got around to petting a sea lion!</h2>
            @guest
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg mt-3 btn-outline-light">Sign up now!</a>
                @endif
            @else
                    <a href="{{ route('lists.index') }}" class="btn btn-primary btn-lg mt-3 btn-outline-light">Go to my lists</a>
            @endguest
        </div>
    </div>
</div>
@endsection
