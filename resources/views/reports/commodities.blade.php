@extends('layouts.app')
@section('title', 'Informe de comodidades')
@section('content')
<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">Comodidades adicionales más demandadas</h6>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Cantidad de veces demandada</th>
                    <th scope="col">Ver más</th>
                </tr>
            </thead>
            <tbody>       
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
    </div>
    <span class="mt-2">Observación: Este listado contiene las comodidades que han sido solicitadas adicionalmente a las comodidades que están previamente incluídas para la tarifa solicitada</span>
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
                
                // Hacer una llamada AJAX para obtener los detalles del commodity
                fetch(`/commodities/${commodityId}`)
                    .then(response => response.json())
                    .then(data => {
                        modal.querySelector('#viewMoreInfoModalLabel').textContent = `Comodidad: ${data.title}`;
                        var bookingsTableBody = modal.querySelector('#bookingsTableBody');
                        bookingsTableBody.innerHTML = '';

                        // Llenar la tabla con los bookings
                        data.bookings.forEach(booking => {
                            var row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${booking.startDate}</td>
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