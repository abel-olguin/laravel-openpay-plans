@extends('layouts.app')

@section('content')
    <div class="mx-auto container py-5">
        <form class="grid grid-cols-2 gap-5" method="POST" action="{{route('plans.subscriptions.store')}}">
            @csrf
            @include('plans::form.form', ['plans' => $plans])
        </form>
    </div>
@endsection
