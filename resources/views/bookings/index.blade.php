@extends('layouts.app')
@section('title', 'Reservas')
@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="container">
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
                    <h6>Estado</h6>
                    <select name="status" class="form-select" style="font-size: 0.8rem">
                        <option value="all" {{request('status') == 'all' ? 'selected' : ''}}>Todas las reservas</option>
                        <option value="actives" {{request('status') == 'actives' ? 'selected' : ''}}>Sólo reservas activas</option>
                        <option value="finished" {{request('status') == 'finished' ? 'selected' : ''}}>Reservas finalizadas</option>
                    </select>
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
                        @php 
                            $disabled = false;
                            if($booking->room->status == 'Cleaning in process'){
                                $requestCleaningButtonText = 'Terminar limpieza';
                                $requestCleaningButtonColor = 'danger';
                                $cleaningModal = 'finishCleaningModal';
                            }
                            elseif($booking->room->status == 'Cleaning requested'){
                                $requestCleaningButtonText = 'Terminar limpieza';
                                $requestCleaningButtonColor = 'danger';
                                $cleaningModal = 'finishCleaningModal';
                            }
                            else{
                                $requestCleaningButtonText = 'Solicitar limpieza';
                                $requestCleaningButtonColor = 'success';
                                $cleaningModal = 'requestCleaningModal';
                            }

                        @endphp

                        <div class="col-sm-12 col-xl-6 justify-content-between">
                            <div class="bg-light rounded h-100 p-4 d-flex flex-column justify-content-between">
                                <div class="row mb-2">
                                    <div class="col-2">
                                        <h6 class="mt-2">#{{$booking->id}}</h6>
                                    </div>
                                    <div class="col-10 text-end">   
                                    @if(empty($booking->actualEndDate))          
                                        <a href="#" type="submit" class="btn btn-{{$requestCleaningButtonColor}} btn-sm request-cleaning-btn @if($disabled == true) disabled @endif" 
                                        data-room-id="{{ $booking->room_id }}" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#{{$cleaningModal}}">{{$requestCleaningButtonText}}</a>
                                    @endif
                                        <a href="{{ route('bookings.edit', $booking->id) }}" type="submit" class="btn btn-primary btn-sm">
                                            @if(empty($booking->actualEndDate))
                                                Editar
                                            @else
                                                Ver
                                            @endif
                                        </a>
                                    @if(empty($booking->actualEndDate))          
                                        <a href="#" type="submit" class="btn btn-info btn-sm"
                                            data-booking-id="{{ $booking->id }}" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#moreActionsModal">
                                        +
                                        </a>
                                    @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <p class="mt-1 mb-1">Huésped: <strong>{{$booking->user->fullName}}</strong></p>
                                    <p class="mt-1 mb-1">Fecha Inicio: <strong>{{ \Carbon\Carbon::parse($booking->startDate)->format('d/m/Y') }}</strong></p>
                                    <p class="mt-1 mb-1">Fecha Fin Pactada: <strong>{{ \Carbon\Carbon::parse($booking->agreedEndDate)->format('d/m/Y') }}</strong></p>
                                    <p class="mt-1 mb-1">Fecha Fin real: 
                                        @if(!empty($booking->actualEndDate))
                                            <strong>{{ \Carbon\Carbon::parse($booking->actualEndDate)->format('d/m/Y') }}</strong>
                                        @else  
                                            <strong>Sin determinar</strong>
                                        @endif
                                    </p>
                                    <p class="mt-1 mb-1">Tarifa: <strong>{{$booking->rate->title}}</strong></p>
                                    <p class="mt-1 mb-1">Nro Habitación: <strong>{{$booking->room->code}}</strong></p>
                                    <p class="mt-1 mb-2">Cant. Personas: <strong>{{$booking->numberOfPeople}}</strong></p>
                                    <p class="mt-1 mb-2">Estado actual de habitación: <strong>{{$booking->room->getStatusFormatted()}}</strong></p>

                                </div>
                                @if(!empty($booking->finalPrice))
                                    <div class="mt-auto">
                                        <div class="bg-dark text-white text-center py-2 bottom-0 start-0 w-100">Precio final: {{ '$' . number_format($booking->finalPrice, 2)}}</div>
                                    </div>
                                @else
                                    <div class="mt-auto">
                                        <div class="bg-secondary text-white text-center py-2 bottom-0 start-0 w-100">Precio provisorio: {{ '$' . number_format($booking->getCalculatedBookingPrice(), 2)}}</div>
                                    </div>
                                @endif

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
</div>

{{-- Request a cleaning modal --}}
<div class="modal fade" id="requestCleaningModal" tabindex="-1" aria-labelledby="requestCleaningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="requestCleaningModalLabel">
                    @php
                        $currentHour = \Carbon\Carbon::now()->format('H:i');
                        $isWithinWorkingHours = $currentHour >= $cleaningWorkingHoursFrom && $currentHour <= $cleaningWorkingHoursTo;
                    @endphp
                    
                    @if($isWithinWorkingHours)
                        ¿Desea confirmar la solicitud?
                    @else
                        Fuera del horario permitido
                    @endif
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($isWithinWorkingHours)
                    <p>Se registrará un período de limpieza para este momento: <b>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</b></p>
                @else
                    <p>No puede solicitar ni terminar una limpieza fuera del horario permitido: 
                        <b>({{ $cleaningWorkingHoursFrom }}</b> - <b>{{ $cleaningWorkingHoursTo }})</b>.
                    </p>
                @endif
            </div>
            <div class="modal-footer">
                @if($isWithinWorkingHours)
                    <form method="POST" action="{{ route('cleanings.requestCleaning') }}">
                        @csrf
                        <input type="hidden" name="room_id" id="roomId">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Volver</button>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </form>
                @else
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                @endif
            </div>
        </div>
    </div>
</div>


{{-- Finish Cleaning as Admin --}}
<div class="modal fade" id="finishCleaningModal" tabindex="-1" aria-labelledby="finishCleaningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('cleanings.finishCleaningAsAdmin') }}">
        @method('PUT')
        @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="finishCleaningModalLabel">
                        @php
                            $currentHour = \Carbon\Carbon::now()->format('H:i');
                            $isWithinWorkingHours = $currentHour >= $cleaningWorkingHoursFrom && $currentHour <= $cleaningWorkingHoursTo;
                        @endphp
                        
                        @if($isWithinWorkingHours)
                            ¿Desea confirmar la finalización?
                        @else
                            Fuera del horario permitido
                        @endif
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($isWithinWorkingHours)
                    <p>Se registrará la finalización de la limpieza en este momento: <b>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}<b></p>
                    <div class="row mb-3">
                        <label for="cleaner_id">¿Quién llevó a cabo la limpieza?</label>
                        <div class="mt-2">
                            <select name="cleaner_id" class="form-select mb-5">
                                <option value="">Seleccione un empleado de limpieza</option>
                                @foreach($cleaners as $cleaner)
                                    <option value="{{ $cleaner->id }}" {{ request()->query('cleaner_id') == $cleaner->id ? 'selected' : '' }}>
                                        {{ $cleaner->fullName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @else
                        <p>No puede solicitar ni terminar una limpieza fuera del horario permitido: 
                            <b>({{ $cleaningWorkingHoursFrom }}</b> - <b>{{ $cleaningWorkingHoursTo }})</b>.
                        </p>
                    @endif
                </div>
                <div class="modal-footer">
                    @if($isWithinWorkingHours)
                        <input type="hidden" name="room_id" id="roomIdFinish">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Volver</button>
                        <button type="submit" class="btn btn-danger">Confirmar finalización</button>
                    @else
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

{{-- More Actions as Admin --}}
<div class="modal fade" id="moreActionsModal" tabindex="-1" aria-labelledby="moreActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        @method('PUT')
        @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="moreActionsModalLabel">¿Qué desea agregar a la reserva #<span id="booking_id_title"></span>? </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        <form method="GET" action="{{ route('bookings.viewExtraCommoditiesForBooking', ['id' => ':booking_id_input']) }}" id="contractForm">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Volver</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"  onclick="openAdditionalServicesModal(this)">Servicio adicional</button>
                        <button type="submit" class="btn btn-primary">Comodidad adicional</button>      
                    </form>
                </div>
            </div>
    </div>
</div>

{{-- Additional Services Modal --}}
<div class="modal fade" id="additionalServicesModal" tabindex="-1" aria-labelledby="additionalServicesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{route('additionalServices.store')}}">
        @csrf
            <input type="hidden" name="booking_id" id="bookingIdInput">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="additionalServicesModalLabel">Servicio adicional - Reserva #<span id="booking_id_title_2"></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                            <div class="col-4"><label>Fecha y hora</label></div>
                            <div class="col-8">
                                <input type="datetime-local" name="dateTime" class="form-control" id="dateTimeInput" disabled>
                            </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><label>Servicio adicional</label></div>
                        <div class="col-8"><input name="title" type="text" class="form-control" required></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4"><label>Precio</label></div>
                        <div class="col-8">
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="priceInput" name="price" placeholder="0.00" step="0.01" min="0" required>
                            </div>                        
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Volver</button>
                    <button type="submit" id="confirmButton" class="btn btn-primary" onclick="disableButton()">Confirmar</button>
                </div>
            </div>
        </form>
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $bookings->links() }}
    </div>
</div>

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var requestCleaningModal = document.getElementById('requestCleaningModal');
        var finishCleaningModal = document.getElementById('finishCleaningModal');
        var moreActionsModal = document.getElementById('moreActionsModal');
        var additionalServicesModalModal = document.getElementById('additionalServicesModal');
        var roomIdInput = document.getElementById('roomId');
        var roomIdFinishInput = document.getElementById('roomIdFinish');
        var bookingIdInput = document.getElementById('bookingIdInput');
        var booking_id_title = document.getElementById('booking_id_title');
        var booking_id_title_2 = document.getElementById('booking_id_title_2');
        var modalFormAction = document.querySelector('#contractForm');

        requestCleaningModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;            
            var roomId = button.getAttribute('data-room-id');
            roomIdInput.value = roomId;
        });

        finishCleaningModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;            
            var roomId = button.getAttribute('data-room-id');
            roomIdFinishInput.value = roomId;
        });

        moreActionsModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;            
            var bookingId = button.getAttribute('data-booking-id');
            booking_id_title.innerText = bookingId;
            booking_id_title_2.innerText = bookingId;
            bookingIdInput.value = bookingId;

            // Actualizar la acción del formulario con el booking_id dinámico
            var formActionUrl = "{{ route('bookings.viewExtraCommoditiesForBooking', ['id' => ':booking_id_input']) }}";
            formActionUrl = formActionUrl.replace(':booking_id_input', bookingId);
            modalFormAction.action = formActionUrl;
        });
    });

    function setBookingId() {
        var bookingId = document.getElementById('booking_id').value;
        document.getElementById('booking_id_title').innerText = bookingId;
    }
    
    function openAdditionalServicesModal(button) {
        var bookingId = button.getAttribute('data-booking-id');
        
        document.getElementById('booking_id_title').innerText = bookingId;
        setTimeout(function() {
            var additionalServicesModal = new bootstrap.Modal(document.getElementById('additionalServicesModal'));
            additionalServicesModal.show();
        }, 300);

        /**DATE TIME*/
        var dateTimeInput = document.getElementById('dateTimeInput');
        
        // Obtener la fecha y hora actual en la zona horaria de Buenos Aires
        var now = new Date();

        // Convertir la hora local a la zona horaria de Buenos Aires usando `toLocaleString`
        var options = { timeZone: 'America/Argentina/Buenos_Aires' };
        var localDate = new Date(now.toLocaleString('en-US', options));

        // Obtener los componentes de la fecha (año, mes, día, hora, minuto)
        var year = localDate.getFullYear();
        var month = String(localDate.getMonth() + 1).padStart(2, '0');  // Los meses empiezan desde 0
        var day = String(localDate.getDate()).padStart(2, '0');
        var hours = String(localDate.getHours()).padStart(2, '0');
        var minutes = String(localDate.getMinutes()).padStart(2, '0');

        // Crear el formato correcto para datetime-local (YYYY-MM-DDTHH:mm)
        var formattedDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

        // Asignar al input de datetime-local
        dateTimeInput.value = formattedDateTime;
    }

    function disableButton() {

        const title = document.querySelector('input[name="title"]');
        const price = document.querySelector('input[name="price"]');
        const dateTime = document.querySelector('input[name="dateTime"]');
        const confirmButton = document.getElementById('confirmButton');

        // Verificar que el servicio adicional y la fecha/hora no estén vacíos
        if (!title.value.trim() || !dateTime.value.trim()) {
            return false;
        }

        // Verificar que el precio sea un número positivo
        if (price.value <= 0) {
            return false;
        }

        confirmButton.disabled = true;
        confirmButton.innerHTML = 'Procesando...';

        const button = document.getElementById('confirmButton');
        button.form.submit(); 
    }

</script>