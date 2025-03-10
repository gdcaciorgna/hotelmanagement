@extends('layouts.app')
@section('title', 'Informe de Comodidades')
@section('content')
<div class="bg-light rounded h-100 p-4">
    <h5 class="mb-4">Comodidades adicionales más demandadas</h5>
    <div class="col-2">
        <form method="GET" action="{{ route('commodities.report') }}">
            <div class="d-flex align-items-end my-4">
                <div class="me-3">
                    <label for="start_date" class="form-label">Desde:</label>
                    <input 
                        type="date" 
                        name="start_date" 
                        class="form-control" 
                        id="start_date" 
                        value="{{ old('start_date', $start_date ?? '') }}"
                        >
                </div>
                <div class="me-3">
                    <label for="end_date" class="form-label">Hasta:</label>
                    <input 
                        type="date" 
                        name="end_date" 
                        class="form-control" 
                        id="end_date" 
                        value="{{ old('end_date', $end_date ?? '') }}"
                        >
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-center">
            <i class="fa fa-exclamation-triangle fa-lg me-2" aria-hidden="true"></i>
            <p class="mb-0">{{ $errors->first() }}</p>
        </div>
    @else
        <div class="table-responsive">
            @if($commodities->isEmpty())
                <hr />
                <div class="text-center">
                    <span class="fs-5">No hay comodidades adicionales solicitadas en el rango de fechas indicado</span>
                </div>
                <hr />
            @else
                <table class="table align-middle">
                    <tbody>
                        <thead>
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Cantidad de veces demandada</th>
                                <th scope="col">Ver más</th>
                            </tr>
                        </thead>
                        @foreach ($commodities as $commodity)
                            <tr>
                                <td>{{$commodity->id}}</td>
                                <td>{{$commodity->title}}</td>
                                <td>{{$commodity->bookings_count}}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-sm-square btn-info m-2" data-bs-toggle="modal" data-bs-target="#viewMoreInfoModal" data-commodity-id="{{ $commodity->id }}">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr />
                <span class="mt-2"><strong>Observación</strong>: Este listado contiene las comodidades que han sido solicitadas adicionalmente a las comodidades que están previamente incluidas para la tarifa solicitada</span>
                <hr />
                <div class="mt-2">
                    <a href="#" id="printScreen" class="btn btn-secondary" onclick="window.print()"><i class="fas fa-print fa-lg"></i></a>
                </div>
            @endif
        </div>
    @endif
</div>

{{-- View More Info Modal --}}
<div class="modal fade" id="viewMoreInfoModal" tabindex="-1" aria-labelledby="viewMoreInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="viewMoreInfoModalLabel">Comodidad:</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Huésped</th>
                            <th>Código de Reserva</th>
                        </tr>
                    </thead>
                    <tbody id="bookingsTableBody">
                    </tbody>
                </table>
            </div>            
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modalTriggerButtons = document.querySelectorAll('.btn[data-bs-toggle="modal"]');
        var modal = document.getElementById('viewMoreInfoModal');

        modalTriggerButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                var commodityId = event.currentTarget.getAttribute('data-commodity-id');

                // Obtener los valores de las fechas desde los campos del formulario
                const startDate = document.getElementById('start_date').value; // Obtener la fecha de inicio
                const endDate = document.getElementById('end_date').value; // Obtener la fecha de fin

                // Construir la URL con los parámetros de fecha
                const url = `/commodities-report/${commodityId}?start_date=${startDate}&end_date=${endDate}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        modal.querySelector('#viewMoreInfoModalLabel').textContent = `Comodidad: ${data.title}`;
                        var bookingsTableBody = modal.querySelector('#bookingsTableBody');
                        bookingsTableBody.innerHTML = ''; // Limpia el contenido previo

                        // Ordena las reservas por fecha de asignación de la comodidad
                        data.bookings.sort((a, b) => new Date(b.commodityAddedDate) - new Date(a.commodityAddedDate));

                        // Muestra las reservas en la tabla
                        data.bookings.forEach(booking => {
                            var row = document.createElement('tr');

                            const originalDate = new Date(booking.commodityAddedDate);
                            const day = String(originalDate.getDate()).padStart(2, '0');
                            const month = String(originalDate.getMonth() + 1).padStart(2, '0');
                            const year = originalDate.getFullYear();

                            // Formato de fecha y hora: dd/mm/yyyy
                            const formattedDateTime = `${day}/${month}/${year}`;

                            row.innerHTML = `
                                <td>${formattedDateTime}</td>
                                <td>${booking.user.firstName} ${booking.user.lastName}</td>
                                <td>${booking.id}</td>
                            `;
                            bookingsTableBody.appendChild(row);
                        });
                    })
                    .catch(error => console.error('Error al obtener los datos del commodity:', error));

            });
        });
    });
</script>

@endsection