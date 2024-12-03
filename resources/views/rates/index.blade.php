@extends('layouts.app')
@section('title', 'Tarifas')
@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="container">
    <div class="row g-12 mb-4">
        <div class="row mb-3">
            <div class="col-sm-3">
                <h3 class="mt-2">Tarifas</h3>
            </div>
            <div class="col-sm-9 text-end">
                <a href="{{route('rates.create')}}" class="btn btn-dark">Agregar nueva tarifa</a>
            </div>
        </div>
    </div>
    <div class="row g-4">
        @foreach ($rates as $rate)
            <div class="col-sm-12 col-xl-4 justify-content-start">
                <div class="bg-light rounded h-100 p-4 d-flex flex-column justify-content-start">
                    <div class="row">
                        <div class="col-8">
                            <span class="text-secondary small">#{{$rate->id}}</span>
                            <h5 class="mt-1">{{$rate->title}}</h5>
                        </div>
                        <div class="col-3">
                            <a href="{{route('rates.edit', $rate->id)}}" type="submit" class="btn btn-primary">Editar</a>
                        </div>
                    </div>
                    <p class="mt-2">{{ \Illuminate\Support\Str::limit($rate->description, 150, '...') }}</p>
                    <p class="mt-2">Precio: <strong>${{$rate->currentPrice}}</strong></p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $rates->links() }}
    </div>
</div>

@endsection
