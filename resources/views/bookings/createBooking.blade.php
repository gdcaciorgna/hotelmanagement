@extends('layouts.app')
@section('title', 'Agregar o editar reserva')
@section('content')

@php
    $roomCode = request('roomCode');
    if($roomCode){
        $selectRoomButtonText = 'Modificar habitación';
        $buttonStyle = 'warning';
    }
    else{
        $selectRoomButtonText = 'Seleccionar habitación';
        $buttonStyle = 'success';
    }

    $startDate = Illuminate\Support\Carbon::parse($startDate)->format('Y-m-d');
    $agreedEndDate = Illuminate\Support\Carbon::parse($agreedEndDate)->format('Y-m-d');
@endphp

<div class="bg-light rounded h-100 p-4">
    <div class="row mb-3">
        <div class="col-sm-3">
            <h6 class="mb-4">Agregar nueva reserva</h6>
        </div>
        <div class="col-sm-9 text-end">
            <a href="{{route('bookings.index')}}" class="btn btn-dark">Ver Reservas</a>
        </div>
    </div>
   
    <form action="{{route('bookings.store')}}" method="POST">
        @csrf
        <input type="hidden" name="action_type" id="action_type" value="">
        <input type="hidden" name="room_code" id="room_code" value="
        @if($roomCode) 
            {{$roomCode}} 
        @endif
        ">
        <input type="hidden" name="action" id="action" value="{{$action}}">

        @if($action == 'edit')
            <div class="row mb-3">
                <label for="id" class="col-sm-3 col-form-label">ID Reserva</label>
                <div class="col-sm-9">
                    <input type="number" class="form-control @error('id') is-invalid @enderror" id="id" name="id" placeholder="101" value="{{ old('code', $booking->id ?? '') }}" readonly>
                    @error('id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror    
                </div>
            </div>
        @endif

        <div class="row mb-3">
            <label for="startDate" class="col-sm-3">Fecha reserva</label>
            <div class="col-sm-9">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
        </div>

        <div class="row mb-3">
            <label for="startDate" class="col-sm-3 col-form-label">Fecha inicio</label>
            <div class="col-sm-9">
                <input type="date" class="form-control @error('startDate') is-invalid @enderror" id="startDate" name="startDate"  @if($roomCode) readonly @endif
                    value="{{ old('startDate', $startDate ?? '') }}">
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
                <input type="date" class="form-control @error('agreedEndDate') is-invalid @enderror" id="agreedEndDate" name="agreedEndDate" @if($roomCode) readonly @endif
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
                    <input class="form-check-input" type="checkbox" name="returnDeposit" id="returnDeposit"  @if($roomCode) readonly @endif
                    {{ isset($returnDeposit) && $returnDeposit ? 'checked' : '' }}>
                </div>
            </div>
        </div> 
        <div class="row mb-3">
            <label for="rate_id" class="col-sm-3 col-form-label">Tarifa</label>
            <div class="col-sm-9">
                <select name="rate_id_display" class="form-select @error('rate_id') is-invalid @enderror" @if($roomCode) disabled @endif onchange="document.getElementById('rate_id').value = this.value;">
                    <option value="">Seleccione una tarifa</option>
                    @foreach($rates as $rate)
                        <option value="{{ $rate->id }}" {{ old('rate_id', $rate_id ?? '') == $rate->id ? 'selected' : '' }}>
                            {{ $rate->title }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="rate_id" id="rate_id" value="{{ old('rate_id', $rate_id ?? '') }}">
                
                @error('rate_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        
        <div class="row mb-3">
            <label for="user_id" class="col-sm-3 col-form-label">Huésped principal</label>
            <div class="col-sm-9">
                <select name="user_id_display" class="form-select @error('user_id') is-invalid @enderror" @if($roomCode) disabled @endif onchange="document.getElementById('user_id').value = this.value;">
                    <option value="">Seleccione un huésped</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $user_id ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->fullName }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="user_id" id="user_id" value="{{ old('user_id', $user_id ?? '') }}">
                                @error('user_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        
        <div class="row mb-3">
            <label for="numberOfPeople" class="col-sm-3 col-form-label">Cantidad de huéspedes</label>
            <div class="col-sm-9">
                <select name="numberOfPeople_display" class="form-select @error('numberOfPeople') is-invalid @enderror" @if($roomCode) disabled @endif onchange="document.getElementById('numberOfPeople').value = this.value;">
                    <option value="">Seleccione una cantidad</option>
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ old('numberOfPeople', $numberOfPeople ?? '') == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor                
                </select>
                <input type="hidden" name="numberOfPeople" id="numberOfPeople" value="{{ old('numberOfPeople', $numberOfPeople ?? '') }}">
                @error('numberOfPeople')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>        

        <div class="row mb-3">
            <label for="selectRoom" class="col-sm-3 col-form-label">Habitación</label>
            <div class="col-sm-9 d-flex align-items-center">
                @if($roomCode)
                    <span class="me-3">N°:{{ $roomCode }}</span>
                @endif
                <button type="submit" id="selectRoom" id="selectRoomButton" class="btn btn-{{$buttonStyle}}" onclick="document.getElementById('action_type').value='select_room';">
                    {{ $selectRoomButtonText }}
                </button>
                @if($roomCode)
                    <button type="button" id="clearButton" class="btn btn-secondary ms-2">Habilitar modificación</button>
                @endif

            </div>
        </div>      
        @if(!empty($stayDays) && $stayDays > 0)
            <div class="row mb-3">
                <p for="stayDays" class="col-sm-3">Días en estadía:</p>
                <p class="col-sm-9">{{ $stayDays }}</p>
            </div>
        @endif
        
        @if(!empty($totalBookingPrice) && $totalBookingPrice > 0)
            <div class="row mb-1">
                <p for="totalBookingPrice" class="col-sm-3">Precio reserva:</p>
                <p class="col-sm-9">
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
            <div class="col-12 d-flex">
                <button type="submit" id="saveBookingButton" class="btn btn-primary" 
                    onclick="document.getElementById('action_type').value='save_booking';">
                    Crear reserva
                </button>
        
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modifyRoomButton = document.getElementById('selectRoom');
    const clearButton = document.getElementById('clearButton');
    const formFields = document.querySelectorAll('form input:not([type="hidden"]), form select, form textarea');

    if (modifyRoomButton) {
        modifyRoomButton.addEventListener('click', function (event) {
            formFields.forEach(field => {
                if (field.hasAttribute('readonly')) {
                    field.removeAttribute('readonly');
                }
                if (field.hasAttribute('disabled')) {
                    field.removeAttribute('disabled');
                }
            });
        });
    }

    if (clearButton) {
        clearButton.addEventListener('click', function () {
            formFields.forEach(field => {
                if (field.type !== 'hidden') {
                    field.removeAttribute('readonly');
                    field.removeAttribute('disabled');
                }
            });
        });
    }
});


</script>
