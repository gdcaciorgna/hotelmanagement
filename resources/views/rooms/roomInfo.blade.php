@extends('layouts.app')
@section('title', 'Agregar o editar habitación')
@section('content')

@php
    if($action == 'edit'){
        $headerText = "Editar Habitación: #{$room->id}";
        $formAction = route('rooms.update', ['id' => $room->id]);
        $method = 'PUT';
        $saveButtonText = 'Publicar';
    }
    else{
        $headerText = "Agregar nueva habitación";
        $formAction = route('rooms.store');
        $method = 'POST';
        $saveButtonText = 'Actualizar';

    }
@endphp

<div class="bg-light rounded h-100 p-4">
    <div class="row mb-3">
        <div class="col-sm-3">
            <h6 class="mb-4"> {{ $headerText}} </h6>
        </div>
        <div class="col-sm-9 text-end">
            <a href="{{route('rooms.index')}}" class="btn btn-dark">Ver habitaciones</a>
        </div>
    </div>
   
    <form action="{{$formAction}}" method="POST">
        @csrf
        @if(isset($method))
            @method($method)
        @endif
        <div class="row mb-3">
            <label for="code" class="col-sm-3 col-form-label">Nro Habitación</label>
            <div class="col-sm-9">
                <input type="number" class="form-control @error('code') is-invalid @enderror" id="code" name="code" placeholder="101" value="{{ old('code', $room->code ?? '') }}">
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
                <input type="number" class="form-control @error('maxOfGuests') is-invalid @enderror" id="maxOfGuests" name="maxOfGuests" placeholder="2" value="{{ old('maxOfGuests', $room->maxOfGuests ?? '')  }}">
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
                <textarea class="form-control" id="description" name="description"> {{ old('description', $room->description ?? '')  }} </textarea>
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
                <select class="form-select" id="userType" name="userType">
                    <option value="Available" 
                        {{ (old('status', isset($user) ? $room->status : '') == 'Receptionist' || $action != 'edit') ? 'selected' : '' }}>
                        Disponible
                    </option>
                    <option value="Unavailable" 
                        {{ (old('status', isset($user) ? $room->status : '') == 'Cleaner') ? 'selected' : '' }}>
                        Inhabilitado
                    </option>
                    <option value="Cleaning" 
                        {{ (old('status', isset($room) ? $room->status : '') == 'Cleaning') ? 'selected' : '' }}>
                        En limpieza
                    </option>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-6">
                    <button type="submit" class="btn btn-primary">{{$saveButtonText}}</button>
            </div>
        </div>
    </form>
</div>
  
@endsection