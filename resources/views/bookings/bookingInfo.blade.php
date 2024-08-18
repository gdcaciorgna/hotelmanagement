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
        $saveButtonText = 'Crear reserva';

    }
@endphp

<div class="bg-light rounded h-100 p-4">
    <div class="row mb-3">
        <div class="col-sm-3">
            <h6 class="mb-4"> {{ $headerText}} </h6>
        </div>
        <div class="col-sm-9 text-end">
            <a href="{{route('bookings.index')}}" class="btn btn-dark">Ver Reservas</a>
        </div>
    </div>
   
    <form action="{{$formAction}}" method="POST">
        @csrf
        @if(isset($method))
            @method($method)
        @endif
        @if($action == 'edit')
            <div class="row mb-3">
                <label for="id" class="col-sm-3 col-form-label">ID Reserva</label>
                <div class="col-sm-9">
                    <input type="number" class="form-control @error('id') is-invalid @enderror" id="id" name="id" placeholder="101" value="{{ old('code', $booking->id ?? '') }}" disabled>
                    @error('id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror    
                </div>
            </div>
        @endif
        <div class="row mb-3">
            <label for="startDate" class="col-sm-3 col-form-label">Fecha inicio</label>
            <div class="col-sm-9">
                <input type="date" class="form-control @error('bornDate') is-invalid @enderror" id="startDate" name="startDate"
                @if(old('startDate')) 
                   value="{{ \Carbon\Carbon::parse(old('startDate'))->format('Y-m-d') }}" 
                @elseif(isset($booking) && $booking->bornDate) 
                   value="{{ \Carbon\Carbon::parse($booking->startDate)->format('Y-m-d') }}" 
                @endif
               >
               @error('startDate')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror  
            </div>
        </div>
        <div class="row mb-3">
            <label for="agreedEndDate" class="col-sm-3 col-form-label">Fecha fin pactada</label>
            <div class="col-sm-9">
                <input type="date" class="form-control @error('bornDate') is-invalid @enderror" id="agreedEndDate" name="agreedEndDate"
                @if(old('agreedEndDate')) 
                   value="{{ \Carbon\Carbon::parse(old('agreedEndDate'))->format('Y-m-d') }}" 
                @elseif(isset($booking) && $booking->bornDate) 
                   value="{{ \Carbon\Carbon::parse($booking->agreedEndDate)->format('Y-m-d') }}" 
                @endif
               >
               @error('agreedEndDate')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror  
            </div>
        </div>
        <div class="row mb-3">
            <label for="numberOfPeople" class="col-sm-3 col-form-label">Cant. de huéspedes</label>
            <div class="col-sm-9">
                <input type="number" class="form-control @error('numberOfPeople') is-invalid @enderror" id="numberOfPeople" name="numberOfPeople" placeholder="2" value="{{ old('numberOfPeople', $booking->numberOfPeople ?? '')  }}">
                @error('numberOfPeople')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror    
            </div>
        </div>
        <div class="row mb-3">
            <legend class="col-form-label col-sm-3 pt-0">Usuario Inhabilitado</legend>
            <div class="col-sm-9">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="status" id="disabledCheckbox" 
                    @if (isset($booking) && $booking->returnDeposit == 0 || old('returnDeposit'))
                        checked 
                    @endif
                    >
                    <label class="form-check-label" for="disabledCheckbox">
                        Devolver depósito
                    </label>
                </div>
            </div>
        </div> 

        <div class="row mb-3">
            <label for="numberOfPeople" class="col-sm-3 col-form-label">Tarifa</label>
            <div class="col-sm-9">
                <select name="rate_title" class="form-select">
                    <option value="">Seleccione una tarifa</option>
                    @foreach($rates as $rate)
                        <option value="{{ $rate->title }}" {{ request('rate_title') == $rate->title ? 'selected' : '' }}>
                            {{ $rate->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label for="numberOfPeople" class="col-sm-3 col-form-label">Habitación</label>
            <div class="col-sm-9">
                <button type="submit" class="btn btn-success ms-auto">Seleccionar habitación</button>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12  d-flex">
                <button type="submit" class="btn btn-primary">{{ $saveButtonText }}</button>

                @if($action == 'edit')
                    <button type="button" class="btn btn-link text-danger p-0 ms-2" data-bs-toggle="modal" data-bs-target="#deleteModal">Eliminar</button>
                    <button type="submit" class="btn btn-success ms-auto">Finalizar Reserva</button>
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