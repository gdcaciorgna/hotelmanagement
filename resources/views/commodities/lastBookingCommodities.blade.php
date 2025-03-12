@extends('layouts.app')
@section('title', 'Mis Comodidades')
@section('content')
<div class="container">
    @if(empty($booking))
        <h2 class="mt-2 mb-4">
            Ups, no tenés asignada una reserva
        </h2>
        <div class="d-flex justify-content-between">
            <p class="fs-6 fw-bold">
                Contactate con el personal de recepción para conocer las habitaciones disponibles y realizar una reserva.
            </p>
            <div>
                <i class="fa fa-phone me-2"></i>
                <a href="tel:+5215512345678" class="text-decoration-none">+52 1 55 1234 5678</a>
            </div>
        </div>
    @else
        <div class="row g-12 mb-4">
            <div class="row mb-3">
                <h3 class="mt-2">Mi Reserva actual</h3>
                    <p class="mt-1 mb-1">ID: <strong>{{ $booking->id }}</strong></p>
                    <p class="mt-1 mb-1">Fecha Inicio: <strong>{{ \Carbon\Carbon::parse($booking->startDate)->format('d/m/Y') }}</strong></p>
                    <p class="mt-1 mb-1">Fecha Fin Pactada: <strong>{{ \Carbon\Carbon::parse($booking->agreedEndDate)->format('d/m/Y') }}</strong></p>
                    <p class="mt-1 mb-1">Tarifa: <strong>{{$booking->rate->title}}</strong></p>
                    <p class="mt-1 mb-1">Nro Habitación: <strong>{{$booking->room->code}}</strong></p>
                    <p class="mt-1 mb-2">Cant. Personas: <strong>{{$booking->numberOfPeople}}</strong></p>
            </div>
        </div>
        <div class="row g-4">
            <h3 class="mt-4">Comodidades contratadas</h3>
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
        @if(!$otherCommodities->isEmpty())
            <div class="row mt-4">
                <h3 class="my-4">También podes reservar</h3>
                @foreach ($otherCommodities as $commodity)
                    <div class="col-sm-12 col-xl-4 justify-content-start mb-4">
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
            <div class="row mt-2 text-center">
                <div class="col-12">
                    @if(now() >= $booking->startDate)
                        <p class="fs-5 fw-bold mb-2">
                            Contactate con el personal de recepción para contratar alguna comodidad adicional.
                        </p>
                    @else
                        <p class="fw-bold mb-2" style="font-size: 1.15rem;">
                            Una vez que comience tu reserva, contactate con el personal de recepción para contratar alguna comodidad adicional.
                        </p>
                    @endif
                    <p class="fs-6">
                        <i class="fa fa-phone me-2"></i>
                        <a href="tel:+5215512345678" class="text-decoration-none">+52 1 55 1234 5678</a>
                    </p>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection