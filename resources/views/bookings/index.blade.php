@extends('layouts.app')
@section('title', 'Reservas')
@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-12 mb-1">
    <div class="row mb-3">
        <div class="col-sm-3">
            <h3 class="mt-2">Reservas</h3>
        </div>
        <div class="col-sm-9 text-end">
            <a href="{{ route('bookings.create') }}" class="btn btn-dark">Agregar nueva reserva</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-2">
        <form method="GET" action="{{ route('bookings.index') }}">
            <div class="row mt-2">
                <h5>Filtrar resultados</h5>
            </div>
            <div class="row mt-4">
                <input type="number" name="booking_id" class="form-control" id="exampleFormControlInput1" placeholder="# Reserva" style="font-size: 0.8rem" value="{{ old('booking_id', request('booking_id')) }}">
            </div>
            <div class="row mt-4">
                <h6>Cant. huéspedes</h6>
                <div class="row mb-3">
                    <div class="d-flex flex-wrap align-items-center">
                        @foreach ([1, 2, 3, 4, 5] as $number)
                            <div class="form-check form-check-inline">
                                <input 
                                    class="form-check-input checkbox-small" 
                                    name="numberOfPeople[]" 
                                    type="checkbox" 
                                    value="{{ $number }}"
                                    {{ in_array($number, request('numberOfPeople', [])) ? 'checked' : '' }}>
                                <label class="form-check-label label-small">{{ $number }}{{ $number == 5 ? '+' : '' }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <h6>Tarifa</h6>
                <select name="rate_title" class="form-select" style="font-size: 0.8rem">
                    <option value="">Seleccione una tarifa</option>
                    @foreach($rates as $rate)
                        <option value="{{ $rate->title }}" {{ request('rate_title') == $rate->title ? 'selected' : '' }}>
                            {{ $rate->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row mt-4">
                <h6>Huésped principal</h6>
                <select name="user_id" class="form-select" style="font-size: 0.8rem">
                    <option value="">Seleccione un huésped</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->fullName }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="row mt-4">
                <h6>Habitación</h6>
                <select name="room_code" class="form-select" style="font-size: 0.8rem">
                    <option value="">Seleccione una habitación</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->code }}" {{ request('room_code') == $room->code ? 'selected' : '' }}>
                            {{ $room->code }}
                        </option>
                    @endforeach
                </select>
            </div>            

            <div class="row mt-4">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </form>
    </div>
    <div class="col-10">
        <div class="container">
            <div class="row g-4">
                @if($bookings->isEmpty())
                    <p>No hay reservas que cumplan con las condiciones buscadas.</p>
                @endif
        
                @foreach ($bookings as $booking)
                    <div class="col-sm-12 col-xl-6 justify-content-between">
                        <div class="bg-light rounded h-100 p-4 d-flex flex-column justify-content-between">
                            <div class="row mb-2">
                                <div class="col-2">
                                    <h6 class="mt-2">#{{$booking->id}}</h6>
                                </div>
                                <div class="col-10 text-end">
                                    <a href="#" type="submit" class="btn btn-success btn-sm">Solicitar limpieza</a>
                                    <a href="{{ route('bookings.edit', $booking->id) }}" type="submit" class="btn btn-primary btn-sm">Editar</a>
                                    <a href="#" type="submit" class="btn btn-info btn-sm">+</a>
                                </div>
                            </div>
                            <div class="row">
                                <p class="mt-1 mb-1">Fecha Inicio: <strong>{{ \Carbon\Carbon::parse($booking->startDate)->format('d/m/Y') }}</strong></p>
                                <p class="mt-1 mb-1">Fecha Fin Pactada: <strong>{{ \Carbon\Carbon::parse($booking->agreedEndDate)->format('d/m/Y') }}</strong></p>
                                <p class="mt-1 mb-1">Fecha Fin real: <strong>{{ \Carbon\Carbon::parse($booking->actualEndDate)->format('d/m/Y') }}</strong></p>
                                <p class="mt-1 mb-1">Huésped principal: <strong>{{$booking->user->fullName}}</strong></p>
                                <p class="mt-1 mb-1">Tarifa: <strong>{{$booking->rate->title}}</strong></p>
                                <p class="mt-1 mb-1">Nro Habitación: <strong>{{$booking->room->code}}</strong></p>
                                <p class="mt-1 mb-1">Cant. Personas: <strong>{{$booking->numberOfPeople}}</strong></p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
