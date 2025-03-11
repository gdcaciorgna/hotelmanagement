@extends('layouts.app')
@section('title', 'Editar Reserva')
@section('content')

@php
    $roomCode = (request('roomCode')) ? request('roomCode') : $roomCode;
    $startDate = (request('startDate')) ? request('startDate') : $startDate;
    $agreedEndDate = (request('agreedEndDate')) ? request('agreedEndDate') : $agreedEndDate;
    $returnDeposit = (request('returnDeposit')) ? request('returnDeposit') : $returnDeposit;
    $rate_id =  (request('rate_id')) ? request('rate_id') : $rate_id;
    $user_id =  (request('user_id')) ? request('user_id') : $user_id;
    $numberOfPeople = (request('numberOfPeople')) ? request('numberOfPeople') : $numberOfPeople;
    
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
            <h6 class="mb-4"> Editar Reserva: #{{$booking->id}} </h6>
        </div>
        <div class="col-sm-9 text-end">
            <a href="{{route('bookings.index')}}" class="btn btn-dark">Ver Reservas</a>
        </div>
    </div>
   
    <form action="{{ route('bookings.update', ['id' => $booking->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="action_type" id="action_type" value="">
        <input type="hidden" name="room_code" id="room_code" value="
        @if($roomCode) 
            {{$roomCode}} 
        @endif
        ">
        <input type="hidden" name="action" id="action" value="{{$action}}">

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

        <div class="row mb-3">
            <label for="startDate" class="col-sm-3">Fecha reserva</label>
            <div class="col-sm-9">{{Illuminate\Support\Carbon::parse($booking->bookingDate)->format('d/m/Y')}}</div>
        </div>

        <div class="row mb-3">
            <label for="startDate" class="col-sm-3 col-form-label">Fecha inicio</label>
            <div class="col-sm-9">
                <input type="date" class="form-control @error('startDate') is-invalid @enderror" 
                       id="startDate" name="startDate" 
                       value="{{ request()->query('startDate', old('startDate', $startDate ?? '')) }}" 
                       @if($roomCode) disabled @endif>
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
                <input type="date" class="form-control @error('agreedEndDate') is-invalid @enderror" 
                       id="agreedEndDate" name="agreedEndDate" 
                       value="{{ request()->query('agreedEndDate', old('agreedEndDate', $agreedEndDate ?? '')) }}" 
                       @if($roomCode) disabled @endif>
                @error('agreedEndDate')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror 
            </div>
        </div>
        
       <div class="row mb-3">
            <legend class="col-form-label col-sm-3 pt-0">Devolver depósito</legend>
            <div class="col-sm-9">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="returnDeposit" id="returnDeposit"  
                        @if($roomCode) disabled @endif
                        {{ request()->query('returnDeposit', isset($returnDeposit) && $returnDeposit ? 'checked' : '') }}>
                </div>
            </div>
        </div> 

        <div class="row mb-3">
            <label for="rate_id" class="col-sm-3 col-form-label">Tarifa</label>
            <div class="col-sm-9">
                <select name="rate_id_display" class="form-select @error('rate_id') is-invalid @enderror" 
                        @if($roomCode) disabled @endif onchange="document.getElementById('rate_id').value = this.value;">
                    <option value="">Seleccione una tarifa</option>
                    @foreach($rates as $rate)
                        <option value="{{ $rate->id }}" {{ request()->query('rate_id', old('rate_id', $rate_id ?? '')) == $rate->id ? 'selected' : '' }}>
                            {{ $rate->title }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="rate_id" id="rate_id" value="{{ request()->query('rate_id', old('rate_id', $rate_id ?? '')) }}">
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
                <select name="user_id_display" class="form-select @error('user_id') is-invalid @enderror" 
                        @if($roomCode) disabled @endif onchange="document.getElementById('user_id').value = this.value;">
                    <option value="">Seleccione un huésped</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request()->query('user_id', old('user_id', $user_id ?? '')) == $user->id ? 'selected' : '' }}>
                            {{ $user->fullName }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="user_id" id="user_id" value="{{ request()->query('user_id', old('user_id', $user_id ?? '')) }}">
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
                <select id="numberOfPeopleSelect" name="numberOfPeople_display" class="form-select @error('numberOfPeople') is-invalid @enderror" 
                        @if($roomCode) disabled @endif onchange="document.getElementById('numberOfPeople').value = this.value;">
                    <option value="">Seleccione una cantidad</option>
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ request()->query('numberOfPeople', old('numberOfPeople', $numberOfPeople ?? '')) == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor                
                </select>
                <input type="hidden" name="numberOfPeople" id="numberOfPeople" value="{{ request()->query('numberOfPeople', old('numberOfPeople', $numberOfPeople ?? '')) }}">
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
                    <span class="me-3">N°: {{ $roomCode }}</span>
                @endif
                @if(empty($booking->finalPrice))           
                    <button type="submit" id="selectRoom" id="selectRoomButton" class="btn btn-{{$buttonStyle}}" onclick="document.getElementById('action_type').value='select_room';">
                        {{ $selectRoomButtonText }}
                    </button>
                    @if($roomCode)
                        <button type="button" id="clearButton" class="btn btn-secondary ms-2">Habilitar modificación</button>
                    @endif
                @endif
            </div>
        </div>      
        @if(!empty($stayDays) && $stayDays > 0)
            <div class="row mb-3 stay-days-info">
                <p for="stayDays" class="col-sm-3">Días en estadía:</p>
                <p class="col-sm-9">{{ $stayDays }}</p>
            </div>
        @endif
            
        @if(empty($booking->finalPrice))           
            @if(!isset($cleanTotalBookingPrice) || (isset($cleanTotalBookingPrice) && $cleanTotalBookingPrice != true) && !empty($totalBookingPrice) && $totalBookingPrice > 0 && !$errors->any())
                <div class="row mb-1 booking-price-info">
                    <p for="totalBookingPrice" class="col-sm-3">Precio reserva provisorio:</p>
                    <p class="col-sm-9">
                        <strong>{{ '$' . number_format($totalBookingPrice, 2) }}</strong>
                        <br> Desglose: 
                        ({{ '$' . number_format($breakdown['basePricePerPersonPerDay'], 2) }} [PBPD] 
                        + {{ '$' . number_format($breakdown['basePricePerRatePerDay'], 2) }} [PTPD] 
                        + {{'$' . number_format($breakdown['additionalCommodities'], 2)}} [PCA]) 
                        * {{$breakdown['numberOfPeople']}} [p] 
                        * {{$breakdown['stayDays']}} [d] 
                        + {{'$' . number_format($breakdown['additionalServices'], 2)}} [PSA] 
                        - {{ '$' . number_format($breakdown['returnDepositValue'], 2) }} [VDep]
                    </p>
                </div>
            
                <ul class="list-group list-group-flush small mb-3 booking-price-info">
                    <li class="list-group-item mb-1"><strong>p:</strong> cantidad de personas</li>
                    <li class="list-group-item mb-1"><strong>d:</strong> cantidad de días</li>
                    <li class="list-group-item mb-1"><strong>PBPD:</strong> Precio base por persona por día</li>
                    <li class="list-group-item mb-1"><strong>PTPD:</strong> Precio tarifa por persona por día</li>
                    <li class="list-group-item mb-1"><strong>PCA:</strong> Precio de comodidad adicional no incluída en la tarifa inicial</li>
                    <li class="list-group-item mb-1"><strong>PSA:</strong> Precio total de los servicios adicionales contratados</li>
                    <li class="list-group-item mb-1"><strong>VDep:</strong> Valor de depósito a devolver (opcional en caso de no encontrar anomalías)</li>
                </ul>
            @endif
        @else
            <div class="row mb-1 booking-price-info">
                <p for="totalBookingPrice" class="col-sm-3">Precio final de reserva:</p>
                <p class="col-sm-9">
                    <strong>{{ '$' . number_format($booking->finalPrice, 2) }}</strong>
                </p>
            </div>
        @endif
   
        @if(empty($booking->finalPrice))
            <div class="row mb-3">
                <div class="col-12 d-flex">
                    <button type="submit" id="saveBookingButton" class="btn btn-primary" 
                        onclick="document.getElementById('action_type').value='save_booking';">
                        Actualizar
                    </button>
            
                    <button type="button" class="btn btn-link text-danger p-0 ms-2" data-bs-toggle="modal" data-bs-target="#deleteModal">Eliminar</button>
                    @if(empty($booking->finalPrice) && \Carbon\Carbon::now()->greaterThanOrEqualTo(\Carbon\Carbon::parse($booking->startDate)))
                        <a href="{{route('bookings.showCheckout', $booking->id)}}" type="submit" class="btn btn-success ms-auto">Finalizar Reserva</a>
                    @endif
                </div>
            </div>
        @endif
        
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

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modifyRoomButton = document.getElementById('selectRoom');
        const clearButton = document.getElementById('clearButton');
        const returnDepositButton = document.getElementById('returnDeposit');
        const formFields = document.querySelectorAll('form input:not([type="hidden"]), form select, form textarea');

        const startDate = document.getElementById('startDate');
        const agreedEndDate = document.getElementById('agreedEndDate');
        const numberOfPeopleSelect = document.getElementById('numberOfPeopleSelect');
        const saveBookingButton = document.getElementById('saveBookingButton');

        function disableSaveBookingButton() {
            saveBookingButton.disabled = true;
        }

        function enableSaveBookingButton() {
            saveBookingButton.disabled = false;
        }

        function blockFieldsExcept(exceptId) {
            formFields.forEach(field => {
                if (field.id !== exceptId) {
                    field.setAttribute('readonly', true);
                    field.setAttribute('disabled', true);
                    addHiddenInput(field);
                }
            });
        }

        function unblockFieldsExcept(exceptId) {
            formFields.forEach(field => {
                if (field.id !== exceptId) {
                    field.removeAttribute('readonly');
                    field.removeAttribute('disabled');
                    removeHiddenInput(field);
                }
            });
        }

        function addHiddenInput(field) {
            const hiddenInputId = `hidden_${field.id}`;
            if (!document.getElementById(hiddenInputId)) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = field.name;
                hiddenInput.id = hiddenInputId;
                hiddenInput.value = field.value;
                field.form.appendChild(hiddenInput);
            }
        }

        function removeHiddenInput(field) {
            const hiddenInputId = `hidden_${field.id}`;
            const hiddenInput = document.getElementById(hiddenInputId);
            if (hiddenInput) {
                hiddenInput.remove();
            }
        }

        // Si se modifica la fecha de inicio, fecha de fin pactada o la cantidad de huéspedes, deshabilitar el botón de actualizar la reserva,
        // para obligar al usuario a consultar nuevamente las habitaciones disponibles con los nuevos datos
        [startDate, agreedEndDate, numberOfPeopleSelect].forEach(field => {
            field.addEventListener('change', disableSaveBookingButton);
        });

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                document.querySelectorAll('.stay-days-info, .booking-price-info').forEach(element => {
                    element.style.display = 'none';
                });
                unblockFieldsExcept('id');
            });
        }

        if (returnDepositButton) {
            returnDepositButton.addEventListener('click', function () {
                unblockFieldsExcept('id'); 
            });
        }

        blockFieldsExcept('id');
    });
</script>
