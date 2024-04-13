@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">Usuarios</h6>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">DNI</th>
                    <th scope="col">Nombre y apellido</th>
                    <th scope="col">Tipo Usuario</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $counter = 1; 
                @endphp
        
                @foreach ($users as $user)
                    @php
                        $disabledStartDateFormatted = $user->disabledStartDate ? date('d/m/Y', strtotime($user->disabledStartDate)) : '';   
                    @endphp
                    <tr>
                        <th scope="row">{{$counter}}</th>
                        <td>{{number_format($user->dni, 0, ',', '.')}}</td>
                        <td>{{$user->fullName}}</td>
                        <td>{{$user->getUserTypeFormatted()}}</td>
                        <td>
                        @if ($user->status == 1)
                            <span><i class="fa fa-check text-success"></i></span>
                        @else
                            <span><i class="fa fa-times text-danger"></i></span>
                            <button type="button" class="btn btn-sm btn-sm-square btn-outline-warning m-2" data-bs-toggle="modal" data-bs-target="#viewDisabledInfoModal" data-disabled-start-date="{{ $disabledStartDateFormatted }}" data-disabled-reason="{{ $user->disabledReason }}">
                                <i class="fa fa-info"></i>
                            </button>                                                       
                        @endif
                        </td>
                        <td>
                            <a href="#" type="button" class="btn btn-sm btn-sm-square btn-outline-primary m-2"><i class="fa fa-edit"></i></a>
                            <button type="button" class="btn btn-sm btn-sm-square btn-outline-danger m-2 deleteButton" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-full-name="{{$user->fullName}}"><i class="fa fa-trash-alt"></i></button>
                        </td>
                        
                    </tr>
                    @php
                        $counter++;
                    @endphp 
                @endforeach
                
            </tbody>
        </table>
    </div>
</div>

{{-- View More Info Modal --}}
<div class="modal fade" id="viewDisabledInfoModal" tabindex="-1" aria-labelledby="viewDisabledInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="viewDisabledInfoModalLabel">Usuario Inhabilitado</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Fecha de inhabilitación: <strong id="disabledStartDate"></strong></p>
                <h5>Motivo de Inhabilitación:</h5>
                <p id="disabledReasonParagraph"></p>
                <br>
            </div>            
        </div>
    </div>
</div>


{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteConfirmationModalLabel">Eliminar usuario</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de querer eliminar el usuario <strong id="userFullName"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger">Guardar cambios</button>
            </div>            
        </div>
    </div>
</div>

<script>
   document.addEventListener('DOMContentLoaded', function() {
    var modalTriggerButtons = document.querySelectorAll('.btn[data-bs-toggle="modal"]');
    var deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
    var deleteButton = document.querySelector('.deleteButton');

    modalTriggerButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            var button = event.currentTarget;
            var disabledStartDate = button.getAttribute('data-disabled-start-date');
            var disabledReason = button.getAttribute('data-disabled-reason');

            var modal = document.getElementById('viewDisabledInfoModal');
            modal.querySelector('#disabledStartDate').textContent = disabledStartDate;
            modal.querySelector('#disabledReasonParagraph').textContent = disabledReason;
        });
    });  

    deleteButton.addEventListener('click', function(event) {
        var fullName = event.currentTarget.getAttribute('data-full-name');
        var modal = document.getElementById('deleteConfirmationModal');
        modal.querySelector('#userFullName').textContent = fullName;
    });
});

</script>



@endsection