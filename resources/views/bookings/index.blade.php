@extends('layouts.app')
@section('title', 'Reservas')
@section('content')
<div class="container">
    <div class="row g-12 mb-4">
        <div class="row mb-3">
            <div class="col-sm-3">
                <h3 class="mt-2">Reservas</h3>
            </div>
            <div class="col-sm-9 text-end">
                <a href="#" class="btn btn-dark">Agregar nueva reserva</a>
            </div>
        </div>
    </div>
    <div class="row g-4">
        @foreach ($bookings as $booking)
            <div class="col-sm-12 col-xl-4 justify-content-between">
                <div class="bg-light rounded h-100 p-4 d-flex flex-column justify-content-between">
                    <div class="row">
                        <div class="col-8">
                            <h6 class="mt-2">Reserva N°: {{$booking->id}}</h6>
                        </div>
                        <div class="col-3">
                            <a href="#" type="submit" class="btn btn-primary">Editar</a>
                        </div>
                    </div>
                    <div class="row">
                        <p class="mt-1 mb-1">Fecha Inicio: <strong>{{$booking->startDate}}</strong></p>
                        <p class="mt-1 mb-1">Fecha Fin Pactada: <strong>{{$booking->agreedEndDate}}</strong></p>
                        <p class="mt-1 mb-1">Fecha Fin real: <strong>{{$booking->actualEndDate}}</strong></p>
                        <p class="mt-1 mb-1">Tarifa: <strong>{{$booking->rate->title}}</strong></p>
                        <p class="mt-1 mb-1">Nro Habitación: <strong>{{$booking->room->code}}</strong></p>
                        <p class="mt-1 mb-1">Cant. Huéspedes: <strong>{{$booking->numberOfPeople}}</strong></p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $bookings->links() }}
    </div>
</div>

@endsection
