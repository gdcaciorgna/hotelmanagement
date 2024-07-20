@extends('layouts.app')
@section('title', 'Comodidades')
@section('content')
<div class="container">
    <div class="row g-12 mb-4">
        <div class="row mb-3">
            <div class="col-sm-3">
                <h3 class="mt-2">Comodidades</h3>
            </div>
            <div class="col-sm-9 text-end">
                <a href="{{route('commodities.create')}}" class="btn btn-dark">Agregar nueva comodidad</a>
            </div>
        </div>
    </div>
    <div class="row g-4">
        @foreach ($commodities as $commodity)
            <div class="col-sm-12 col-xl-4 justify-content-start">
                <div class="bg-light rounded h-100 p-4 d-flex flex-column justify-content-start">
                    <div class="row">
                        <div class="col-8">
                            <span class="text-secondary small">#{{$commodity->id}}</span>
                            <h5 class="mt-1">{{$commodity->title}}</h5>
                        </div>
                        <div class="col-3">
                            <a href="{{route('commodities.edit', $commodity->id)}}" type="submit" class="btn btn-primary">Editar</a>
                        </div>
                    </div>
                    <p class="mt-2">{{ \Illuminate\Support\Str::limit($commodity->description, 150, '...') }}</p>
                    <p class="mt-2">Precio: <strong>${{$commodity->currentPrice}}</strong></p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $commodities->links() }}
    </div>
</div>

@endsection
