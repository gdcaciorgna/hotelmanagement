@extends('layouts.app')
@section('title', 'Reservas')
@section('content')
<div class="row g-12 mb-1">
    <div class="row mb-2">
        <div class="col-9">
            <h3>Habitaciones disponibles</h3>
            <p class="mb-1">Cantidad de huéspedes: <strong>{{$numberOfPeople}}</strong></p>
            <p>Fecha solicitada: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($agreedEndDate)->format('d/m/Y') }}</strong></p>
        </div>
        <div class="col-3 d-flex align-items-center justify-content-end">
            <a href="#" type="submit" class="btn btn-danger">Volver a la reserva</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="container">
            <div class="row g-4">
                <div class="container">
                    <div class="row g-4">
                            @if($rooms->isEmpty())
                                <p>No hay habitaciones que cumplan con las condiciones buscadas.</p>
                            @endif
                    
                            @foreach ($rooms as $room)
                                <div class="col-sm-12 col-xl-6 justify-content-between">
                                    <div class="bg-light rounded h-100 p-4 d-flex flex-column justify-content-between">
                                        <div class="row mb-2">
                                            <div class="col-7">
                                            <p class="mt-1 mb-1 small">Número habitación: <strong>{{$room->code}}</strong></p>
                                            <p class="mt-1 mb-1 small">Cantidad máx huéspedes: <strong>{{$room->maxOfGuests}}</strong></p>
                                            <br>
                                            <p class="mt-1 mb-1" style="font-size: 12px">Descripción: {{$room->description}}</p>
                                            </div>
                                            <div class="col-5 text-end">
                                                <a href="#" type="submit" class="btn btn-success btn-sm" style="font-size: 12px;">Seleccionar habitación</a>
                                                <img src="{{asset('/img/rooms/' . $room->image)}}" alt="Habitación genérica" class="img-fluid mt-3" style="height: 90%; object-fit: cover;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $rooms->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
