@extends('layouts.app')
@section('title', 'Comodidades')
@section('content')
<div class="container">
    <div class="row g-12 mb-4">
        <div class="row mb-3">
            <h3 class="mt-2">Mi Reserva actual</h3>
                <p class="mt-1 mb-1">ID: <strong>{{ $booking->id }}</strong></p>
                <p class="mt-1 mb-1">Fecha Inicio: <strong>{{ \Carbon\Carbon::parse($booking->startDate)->format('d/m/Y') }}</strong></p>
                <p class="mt-1 mb-1">Fecha Fin Pactada: <strong>{{ \Carbon\Carbon::parse($booking->agreedEndDate)->format('d/m/Y') }}</strong></p>
                <p class="mt-1 mb-1">Fecha Fin real: <strong>{{ \Carbon\Carbon::parse($booking->actualEndDate)->format('d/m/Y') }}</strong></p>
                <p class="mt-1 mb-1">Huésped principal: <strong>{{$booking->user->fullName}}</strong></p>
                <p class="mt-1 mb-1">Tarifa: <strong>{{$booking->rate->title}}</strong></p>
                <p class="mt-1 mb-1">Nro Habitación: <strong>{{$booking->room->code}}</strong></p>
                <p class="mt-1 mb-2">Cant. Personas: <strong>{{$booking->numberOfPeople}}</strong></p>
        </div>
    </div>
    <div class="row g-4">
        <h3 class="mt-4">Mis comodidades</h3>
        @foreach ($activeCommodities as $commodity)
            <div class="col-sm-12 col-xl-4 justify-content-start">
                <div class="bg-light rounded h-100 p-4 d-flex flex-column justify-content-start">
                    <div class="row">
                        <div class="col-12">
                            <span class="text-secondary small">#{{$commodity->id}}</span>
                            <h5 class="mt-1">{{$commodity->title}}</h5>
                        </div>
                    </div>
                    <p class="mt-2">{{ \Illuminate\Support\Str::limit($commodity->description, 150, '...') }}</p>
                    <div class="mt-auto">
                        <div class="bg-success text-white text-center py-2 bottom-0 start-0 w-100">Contratado</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row mt-4">
        <h3 class="mt-4">También podes reservar</h3>
        @foreach ($otherCommodities as $commodity)
        <div class="col-sm-12 col-xl-4 justify-content-start">
            <div class="bg-light rounded h-100 p-4 d-flex flex-column justify-content-start">
                <div class="row">
                    <div class="col-12">
                        <span class="text-secondary small">#{{$commodity->id}}</span>
                        <h5 class="mt-1">{{$commodity->title}}</h5>
                    </div>
                </div>
                <p class="mt-2">{{ \Illuminate\Support\Str::limit($commodity->description, 150, '...') }}</p>
                <div class="mt-auto">
                    <div class="bg-dark text-white text-center py-2 bottom-0 start-0 w-100">Precio final: {{ '$' . number_format($commodity->currentPrice, 2)}}</div>
                </div>
            </div>
        </div>
    @endforeach
    </div>

    <div class="row mt-4">
        <span style="font-size:20px; font-weight:bold">Contactate con el personal de recepción para llevar a cabo la reserva de alguna comodidad adicional. Llamar al <a href="#">+52 1 55 1234 5678</a> </span>
    </div>
</div>

@endsection
