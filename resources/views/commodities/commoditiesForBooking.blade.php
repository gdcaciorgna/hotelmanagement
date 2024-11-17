@extends('layouts.app')
@section('title', 'Commodities for booking')
@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="container">
    <div class="row g-12 mb-4">
        <div class="row mb-3">
            <h3 class="mt-2">Reserva #{{ $booking->id }}</h3>
                <p class="mt-1 mb-1">Fecha Inicio: <strong>{{ \Carbon\Carbon::parse($booking->startDate)->format(
                    'd/m/Y') }}</strong></p>
                <p class="mt-1 mb-1">Fecha Fin Pactada: <strong>{{ \Carbon\Carbon::parse($booking->agreedEndDate)->format('d/m/Y') }}</strong></p>
                <p class="mt-1 mb-1">Tarifa: <strong>{{$booking->rate->title}}</strong></p>
                <p class="mt-1 mb-1">Nro Habitación: <strong>{{$booking->room->code}}</strong></p>
                <p class="mt-1 mb-2">Cant. Personas: <strong>{{$booking->numberOfPeople}}</strong></p>
        </div>
    </div>
    @if($rateCommodities->isEmpty())
        <p>No hay reservas que cumplan con las condiciones buscadas.</p>
    @else
        <div class="row mt-4 g-4">
            <h3 class="mt-4">Comodidades incluídas para la tarifa: {{$booking->rate->title}}</h3>
            @foreach ($rateCommodities as $commodity)
                <div class="col-sm-12 col-xl-4 justify-content-start">
                    <div class="bg-light rounded h-100 p-4 d-flex flex-column justify-content-start">
                        <div class="row">
                            <div class="col-12">
                                <span class="text-secondary small">#{{$commodity->id}}</span>
                                <h5 class="mt-1">{{$commodity->title}}</h5>
                            </div>
                        </div>
                        <p class="mt-2">{{ \Illuminate\Support\Str::limit($commodity->description, 150, '...') }}</p>
                        <div class="mt-auto">
                            <div class="bg-secondary text-white text-center py-2 bottom-0 start-0 w-100">Incluído</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    @if($bookingCommodities->isEmpty())
        <p>No hay reservas que cumplan con las condiciones buscadas.</p>
    @else
        <div class="row mt-4 g-4">
            <h3 class="mt-4">Comodidades adicionales contratadas</h3>
            @foreach ($bookingCommodities as $commodity)
                <div class="col-sm-12 col-xl-4 justify-content-start">
                    <div class="bg-light rounded h-100 p-4 d-flex flex-column justify-content-start">
                        <div class="row">
                            <div class="col-12">
                                <span class="text-secondary small">#{{$commodity->id}}</span>
                                <h5 class="mt-1">{{$commodity->title}}</h5>
                            </div>
                        </div>
                        <p class="mt-2">{{ \Illuminate\Support\Str::limit($commodity->description, 150, '...') }}</p>
                        <div class="mt-auto">
                            <div class="bg-success text-white text-center py-2 bottom-0 start-0 w-100">Contratado</div>
                            <form method="POST" action="{{route('bookings.deleteCommodity')}}">
                                @method('DELETE')
                                @csrf
                                <input type="hidden" name="booking_id" id="booking_id" value="{{$booking->id}}">
                                <input type="hidden" name="commodity_id" id="commodity_id" value="{{$commodity->id}}">
                                <div class="text-center mt-2">
                                    <button type="submit" class="btn btn-link text-danger p-0 m-0" style="text-decoration: none;">Quitar comodidad</button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    @if($availableCommodities->isEmpty())
        <p>No hay reservas que cumplan con las condiciones buscadas.</p>
    @else
    <div class="row mt-4 g-4">
        <h3 class="mt-4">También podes reservar</h3>
        @foreach ($availableCommodities as $commodity)
        <div class="col-sm-12 col-xl-4 justify-content-start">
            <div class="bg-light rounded h-100 p-4 d-flex flex-column justify-content-start">
                <div class="row">
                    <div class="col-12">
                        <span class="text-secondary small">#{{$commodity->id}}</span>
                        <h5 class="mt-1">{{$commodity->title}}</h5>
                    </div>
                </div>
                <p class="mt-2">{{ \Illuminate\Support\Str::limit($commodity->description, 150, '...') }}</p>
                <p class="mt-2">Precio final: {{ '$' . number_format($commodity->currentPrice, 2)}}</p>
                <div class="mt-auto">
                    <form action="#">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contractModal"
                            data-booking-id="{{ $booking->id }}"
                            data-commodity-id="{{ $commodity->id }}"
                            data-commodity-title="{{ $commodity->title }}"
                            data-commodity-price="{{ $commodity->currentPrice }}">
                            Contratar adicional
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Modal para contratar comodidad adicional -->
<div class="modal fade" id="contractModal" tabindex="-1" aria-labelledby="contractModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contractModalLabel">Contratar Comodidad Adicional al booking #<span id="booking_id"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('bookings.addCommodity', ['id' => ':booking_id_input']) }}" method="POST" id="contractForm">
                    @csrf
                    <input type="hidden" name="booking_id" id="booking_id_input">
                    <input type="hidden" name="commodity_id" id="commodity_id">
                    <div class="mb-3">
                        <label for="commodity_id" class="form-label">Comodidad Adicional</label>
                        <input type="text" class="form-control" id="commodity_title" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Precio</label>
                        <input type="text" class="form-control" id="commodity_price" readonly>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-success">Confirmar Contratación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Evento para cuando el modal se abre
    var contractModal = document.getElementById('contractModal');
    contractModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // El botón que abre el modal
        var bookingId = button.getAttribute('data-booking-id');
        var commodityId = button.getAttribute('data-commodity-id');
        var commodityTitle = button.getAttribute('data-commodity-title');
        var commodityPrice = button.getAttribute('data-commodity-price');
        
        // Asignar los valores al formulario dentro del modal
        var modalBookingId = contractModal.querySelector('#booking_id');
        var modalCommodityId = contractModal.querySelector('#commodity_id');
        var modalBookingIdInput = contractModal.querySelector('#booking_id_input');
        var modalCommodityTitle = contractModal.querySelector('#commodity_title');
        var modalCommodityPrice = contractModal.querySelector('#commodity_price');
        var modalFormAction = contractModal.querySelector('#contractForm');
        
        modalBookingId.innerText = bookingId;
        modalCommodityId.value = commodityId;
        modalBookingIdInput.value = bookingId;
        modalCommodityTitle.value = commodityTitle;
        modalCommodityPrice.value = commodityPrice;
        
        // Actualizar la acción del formulario con el booking_id dinámico
        modalFormAction.action = "{{ route('bookings.addCommodity', ['id' => ':booking_id']) }}".replace(':booking_id', bookingId);
    });
</script>



@endsection
