@extends('layouts.app')
@section('title', "Comodidad para la Reserva")
@section('content')

<div class="bg-light rounded h-100 p-4">
    <div class="row mb-3">
        <div class="col-sm-4">
            <h6 class="mb-4">Reservar comodidad extra</h6>
        </div>
        <div class="col-sm-8 text-end">
            <a href="{{ route('bookings.viewExtraCommoditiesForBooking', ['id' => $booking->id]) }}" class="btn btn-dark">Volver</a>
        </div>
    </div>
   
    <form action="{{ route('bookings.addCommodity', ['id' => $booking->id]) }}" method="POST">
        @csrf

        <input type="hidden" name="booking_id" id="booking_id_input" value="{{ $booking->id }}">
        <input type="hidden" name="commodity_id" id="commodity_id" value="{{ $commodity->id }}">

        <div class="row mb-3">
            <label for="title" class="col-sm-3 col-form-label">Título</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" value="{{ $commodity->title }}" disabled>
                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror    
            </div>
        </div>

        <div class="row mb-3">
            <label for="currentPrice" class="col-sm-3 col-form-label">Precio</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" value="{{ $commodity->currentPrice }}" disabled>
        
            </div>
        </div>
        <div class="row mb-3">
            <label for="description" class="col-sm-3 col-form-label">Descripción</label>
            <div class="col-sm-9">
                <textarea style="min-height: 100px;" class="form-control" disabled>{{ $commodity->description }}</textarea>
            </div>
        </div>
        <div class="row mb-3">
            <label for="description" class="col-sm-3 col-form-label">Código de reserva</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" value="{{ $booking->id }}" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary">Reservar</button>
            </div>
        </div>
    </form>

</div>

@endsection