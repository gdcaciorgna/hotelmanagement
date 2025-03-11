@extends('layouts.app')
@section('title', 'Habitaciones Disponibles')
@section('content')
<div class="row g-12 mb-1">
    <div class="row mb-2">
        <div class="col-9">
            <h3>Habitaciones disponibles</h3>
            <p class="mb-1">Cantidad de huéspedes: <strong>{{$numberOfPeople}}</strong></p>
            <p>Fecha solicitada: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong> al <strong>{{ \Carbon\Carbon::parse($agreedEndDate)->format('d/m/Y') }}</strong></p>
        </div>
        @if($action == 'create')
            <div class="col-3 d-flex align-items-center justify-content-end">
                <a href="{{ route('bookings.create', [
                    'startDate' => $startDate,
                    'agreedEndDate' => $agreedEndDate,
                    'numberOfPeople' => $numberOfPeople,
                    'returnDeposit' => $returnDeposit,
                    'rate_id' => $rate_id,
                    'user_id' => $user_id,
                    'cleanTotalBookingPrice' => true
                ]) }}" class="btn btn-danger">Volver a la reserva</a>            
            </div>
        @elseif($action == 'edit')
            <div class="col-3 d-flex align-items-center justify-content-end">
                <a href="{{ route('bookings.edit', $bookingId)}}" class="btn btn-danger">Volver a la reserva</a>                    
            </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="container">
            <div class="row g-4">
                <div class="container">
                    <div class="row g-4">
                            @if($rooms->isEmpty())
                                <hr />
                                <div class="d-flex justify-content-center" style="min-height: 45px;">
                                    <span class="fs-5">No hay habitaciones que cumplan con las condiciones buscadas</span>
                                </div>
                                <hr />
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
                                                @php
                                                if($action == 'create') 
                                                        $route = route('bookings.create', ['roomCode' => $room->code, 'startDate' => $startDate, 'agreedEndDate' =>$agreedEndDate,  'numberOfPeople' => $numberOfPeople, 'returnDeposit' => $returnDeposit, 'rate_id' => $rate_id, 'user_id' => $user_id]);
                                                elseif($action == 'edit')
                                                        $route = route('bookings.edit', ['id'=> $bookingId, 'roomCode' => $room->code, 'startDate' => $startDate, 'agreedEndDate' =>$agreedEndDate,  'numberOfPeople' => $numberOfPeople, 'returnDeposit' => $returnDeposit, 'rate_id' => $rate_id, 'user_id' => $user_id, 'cleanTotalBookingPrice' => true]);
                                                @endphp
                                                <a href="{{$route}}" type="submit" class="btn btn-success btn-sm" style="font-size: 12px;">Seleccionar habitación</a>
                                                <img src="{{asset('/img/rooms/' . $room->image)}}" alt="{{$room->image}}" class="img-fluid mt-3" style="height: 90%; object-fit: cover;">
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
