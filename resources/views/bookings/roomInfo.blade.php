@extends('layouts.app')
@section('title', 'Agregar o editar reserva')
@section('content')

@php
    if($action == 'edit'){
        $headerText = "Editar Reserva: #{$booking->id}";
        $formAction = route('bookings.update', ['id' => $booking->id]);
        $method = 'PUT';
        $saveButtonText = 'Actualizar';
    }
    else{
        $headerText = "Agregar nueva reserva";
        $formAction = route('bookings.store');
        $method = 'POST';
        $saveButtonText = 'Publicar';

    }
@endphp

<div class="bg-light rounded h-100 p-4">
    <div class="row mb-3">
        <div class="col-sm-3">
            <h6 class="mb-4"> {{ $headerText}} </h6>
        </div>
        <div class="col-sm-9 text-end">
            <a href="{{route('bookings.index')}}" class="btn btn-dark">Ver habitaciones</a>
        </div>
    </div>
   
    <form action="{{$formAction}}" method="POST">
        @csrf
        @if(isset($method))
            @method($method)
        @endif
        <div class="row mb-3">
            <label for="code" class="col-sm-3 col-form-label">Nro Reserva</label>
            <div class="col-sm-9">
                <input type="number" class="form-control @error('code') is-invalid @enderror" id="code" name="code" placeholder="101" value="{{ old('code', $booking->code ?? '') }}">
                @error('code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror    
            </div>
        </div>
        <div class="row mb-3">
            <label for="maxOfGuests" class="col-sm-3 col-form-label">Cant. máxima de huéspedes</label>
            <div class="col-sm-9">
                <input type="number" class="form-control @error('maxOfGuests') is-invalid @enderror" id="maxOfGuests" name="maxOfGuests" placeholder="2" value="{{ old('maxOfGuests', $booking->maxOfGuests ?? '')  }}">
                @error('maxOfGuests')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror    
            </div>
        </div>
        <div class="row mb-3">
            <label for="description" class="col-sm-3 col-form-label">Descripción</label>
            <div class="col-sm-9">
                <textarea class="form-control" id="description" name="description" placeholder="Escribe aquí la descripción...">{{ old('description', $booking->description ?? '')}}</textarea>
                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror    
            </div>
        </div>
        <div class="row mb-3">
            <label for="description" class="col-sm-3 col-form-label">Estado actual</label>
            <div class="col-sm-9">
                <select class="form-select" id="status" name="status">
                    <option value="Available" 
                        {{ (old('status', isset($booking) ? $booking->status : '') == 'Available' || $action != 'edit') ? 'selected' : '' }}>
                        Disponible
                    </option>
                    <option value="Unavailable" 
                        {{ (old('status', isset($booking) ? $booking->status : '') == 'Unavailable') ? 'selected' : '' }}>
                        Inhabilitado
                    </option>
                    <option value="Cleaning" 
                        {{ (old('status', isset($booking) ? $booking->status : '') == 'Cleaning') ? 'selected' : '' }}>
                        En limpieza
                    </option>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary">{{ $saveButtonText }}</button>
                @if($action == 'edit')
                    <button type="button" class="btn btn-link text-danger p-0 ms-2" data-bs-toggle="modal" data-bs-target="#deleteModal">Eliminar</button>
                @endif
            </div>
        </div>
    </form>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

</div>

<!-- Modal para confirmación de eliminación -->
@if($action == 'edit')
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta reserva?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{route('bookings.destroy', $booking->id)}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar Reserva</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection