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

    $roomCode = request('roomCode');
    if($roomCode){
        $selectRoomButtonText = 'Modificar habitación';
        $buttonStyle = 'warning';
    }
    else{
        $selectRoomButtonText = 'Seleccionar habitación';
        $buttonStyle = 'success';
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
        <input type="hidden" name="action_type" id="action_type" value="">
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
                <input type="date" class="form-control @error('startDate') is-invalid @enderror" id="startDate" name="startDate"
                value="{{ old('startDate', isset($startDate) ? $startDate : '') }}">
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
                <input type="date" class="form-control @error('agreedEndDate') is-invalid @enderror" id="agreedEndDate" name="agreedEndDate"
                value="{{ old('agreedEndDate', isset($agreedEndDate) ? $agreedEndDate : '') }}">
                @error('agreedEndDate')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror  
            </div>
        </div>
    
        <div class="row mb-3">
            <legend class="col-form-label col-sm-3 pt-0">Abona depósito</legend>
            <div class="col-sm-9">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="returnDeposit" id="returnDeposit"
                    {{ isset($returnDeposit) && $returnDeposit ? 'checked' : '' }}>
                </div>
            </div>
        </div> 
        <div class="row mb-3">
            <label for="rate_id" class="col-sm-3 col-form-label">Tarifa</label>
            <div class="col-sm-9">
                <select name="rate_id" class="form-select">
                    <option value="">Seleccione una tarifa</option>
                    @foreach($rates as $rate)
                        <option value="{{ $rate->id }}" {{ request('rate_id') == $rate->id ? 'selected' : '' }}>
                            {{ $rate->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        

        <div class="row mb-3">
            <label for="numberOfPeople" class="col-sm-3 col-form-label">Cantidad de huéspedes</label>
            <div class="col-sm-9">
                <select name="numberOfPeople" class="form-select">
                    <option value="">Seleccione una cantidad</option>
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ request('numberOfPeople') == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor                
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label for="selectRoom" class="col-sm-3 col-form-label">Habitación</label>
            <div class="col-sm-9 d-flex align-items-center">
                @if($roomCode)
                    <span class="me-3">N°:{{ $roomCode }}</span>
                @endif
                <button type="submit" id="selectRoom" class="btn btn-{{$buttonStyle}}" onclick="document.getElementById('action_type').value='select_room';">
                    {{ $selectRoomButtonText }}
                </button>
            </div>
        </div>      
        
        @if(!empty($stayDays) && $stayDays > 0)
            <div class="row mb-3">
                <p for="selectRoom" class="col-sm-3">Días en estadía:</p>
                <p for="selectRoom" class="col-sm-9">{{ $stayDays }}</p>
            </div>
        @endif
        
        @if(!empty($totalBookingPrice) && $totalBookingPrice > 0)
            <div class="row mb-1">
                <p for="selectRoom" class="col-sm-3">Precio reserva:</p>
                <p for="selectRoom" class="col-sm-9">
                    <strong>{{ '$' . number_format($totalBookingPrice, 2) }}</strong>
                    <br> Desglose: 
                    ({{ '$' . number_format($breakdown['basePricePerPersonPerDay'], 2) }} [PBPD] 
                    + {{ '$' . number_format($breakdown['basePricePerRatePerDay'], 2) }} [PTPD] 
                    + $0.00 [PCA]) 
                    * {{$breakdown['numberOfPeople']}} [p] 
                    * {{$breakdown['stayDays']}} [d] 
                    + $0 [PSA] 
                    - {{ '$' . number_format($breakdown['returnDepositValue'], 2) }} [VDep]
                </p>
            </div>
        
            <ul class="list-group list-group-flush small mb-3">
                <li class="list-group-item mb-1"><strong>p:</strong> cantidad de personas</li>
                <li class="list-group-item mb-1"><strong>d:</strong> cantidad de días</li>
                <li class="list-group-item mb-1"><strong>PBPD:</strong> Precio base por persona por día</li>
                <li class="list-group-item mb-1"><strong>PTPD:</strong> Precio tarifa por persona por día</li>
                <li class="list-group-item mb-1"><strong>PCA:</strong> Precio de comodidad adicional no incluída en la tarifa inicial</li>
                <li class="list-group-item mb-1"><strong>PSA:</strong> Precio total de los servicios adicionales contratados</li>
                <li class="list-group-item mb-1"><strong>VDep:</strong> Valor de depósito a devolver (opcional en caso de no encontrar anomalías)</li>
            </ul>
        @endif
    

        <div class="row mb-3">
            <div class="col-12  d-flex">
                <button type="submit" class="btn btn-primary" disabled>{{ $saveButtonText }}</button>

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