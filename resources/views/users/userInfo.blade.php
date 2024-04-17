@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')

@php
    if($action == 'edit'){
        $headerText = "Editar Usuario: #{$user->id}";
        $formAction = route('users.edit', $user->id);
    }
    else{
        $headerText = "Agregar nuevo usuario";
        $formAction = route('users.store');
    }
@endphp

<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">
        {{ $headerText}}
    </h6>
    <form action="{{$formAction}}" method="POST">
        @csrf
        <div class="row mb-3">
            <label for="dni" class="col-sm-3 col-form-label">DNI</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="dni" name="dni" data-bs-decimals="0" data-bs-step="1" placeholder="12.345.678" value="{{ old('dni', $user->dni ?? '')  }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <label for="name" class="col-sm-3 col-form-label">Nombre y apellido</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Juan Carlos Pérez" value="{{ old('fullName', $user->fullName ?? '')  }}">
            </div>
        </div>
        <div class="row mb-3">
            <label for="name" class="col-sm-3 col-form-label">Correo electrónico</label>
            <div class="col-sm-9">
                <input type="email" class="form-control" id="email" name="email" placeholder="juanperez@gmail.com" value="{{ old('email', $user->email ?? '')  }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <label for="fechaNacimiento" class="col-sm-3 col-form-label">Fecha de Nacimiento:</label>
            <div class="col-sm-9">
                <input type="date" class="form-control" id="bornDate" name="bornDate"
                @if(old('bornDate')) 
                   value="{{ \Carbon\Carbon::parse(old('bornDate'))->format('Y-m-d') }}" 
                @elseif(isset($user) && $user->bornDate) 
                   value="{{ \Carbon\Carbon::parse($user->bornDate)->format('Y-m-d') }}" 
                @endif
               >
            </div>
        </div>
                           
        <fieldset class="row mb-3">
            <legend class="col-form-label col-sm-3 pt-0">Tipo Usuario</legend>
            <div class="col-sm-9">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="userType"
                        @if(old('userType') == 'Receptionist' || (isset($user) && $user->userType == 'Receptionist')) 
                            checked 
                        @endif
                        id="gridReceptionist">
                    <label class="form-check-label" for="gridReceptionist">
                        Recepcionista / Admin
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="userType" 
                        @if(old('userType') == 'Cleaner' || (isset($user) && $user->userType == 'Cleaner')) 
                            checked 
                        @endif
                        id="gridCleaner">
                    <label class="form-check-label" for="gridCleaner">
                        Empleado de limpieza
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="userType"
                        id="gridGuest" value="Guest"
                        @if(old('userType', 'Guest') == 'Guest' && !isset($user)) 
                            checked 
                        @elseif(old('userType') == 'Guest' || (isset($user) && $user->userType == 'Guest')) 
                            checked 
                        @endif
                        >
                    <label class="form-check-label" for="gridGuest" name="userType">
                        Huésped
                    </label>
                </div>
            </div>
        </fieldset>        
        <div class="row mb-3">
            <legend class="col-form-label col-sm-3 pt-0">Usuario Inhabilitado</legend>
            <div class="col-sm-9">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="status" id="disabledCheckbox" 
                    @if (isset($user) && $user->status == 0 || old('status'))
                        checked 
                    @endif
                    >
                    
                    <label class="form-check-label" for="disabledCheckbox">
                        Inhabilitar Usuario
                    </label>
                </div>
                <div class="mt-3" id="dateInput"
                @if (isset($user) && $user->status == 0 || old('status'))
                    style="display:block"
                @else
                    style="display:none"
                @endif
            >
                <label for="disabledStartDateInput" class="form-label">Fecha de Inhabilitación:</label>
                <input type="date" class="form-control" id="disabledStartDateInput" name="disabledStartDate" 
                       
                       @if(old('disabledStartDate')) 
                        value="{{ \Carbon\Carbon::parse(old('disabledStartDate'))->format('Y-m-d') }}" 
                        @elseif(isset($user) && $user->disabledStartDate) 
                        value="{{ \Carbon\Carbon::parse($user->disabledStartDate)->format('Y-m-d') }}" 
                        @endif
                       @if (isset($user) && $user->status == 0 || old('status'))
                           style="display:block"
                       @endif
                >
                </div>            
                <div class="mt-3" id="disabledReason" value="{{ old('disabledReason', $user->disabledReason ?? '')  }}"
                @if (isset($user) && $user->status == 0 || old('status'))
                    style="display:block"
                @else
                    style="display:none"
                @endif
                >
                    <label for="disabledReasonTextarea" class="form-label">Motivo de Inhabilitación:</label>
                    <textarea class="form-control" id="disabledReasonTextarea" name="disabledReason"> {{ old('disabledReason', $user->disabledReason ?? '')  }} </textarea>
                </div>
            </div>
        </div>    
     
        <div class="row mb-3">
            <div class="col-sm-3">
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                    Modificar contraseña
                </button>
            </div>
            <div class="col-sm-9 text-end">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>
  
<!-- Change password modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="changePasswordModalLabel">Modificar contraseña</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row mb-3">
                <label for="inputPassword3" class="col-sm-4 col-form-label">Nueva contraseña</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" id="inputPassword3">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputPassword3" class="col-sm-4 col-form-label">Repetir contraseña</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" id="inputPassword3">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary">Guardar cambios</button>
        </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dniInput = document.getElementById('dni');
    
        function formatDniValue(value) {
            return value.replace(/\D/g, '').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }

        dniInput.value = formatDniValue(dniInput.value);

        dniInput.addEventListener('input', function(event) {
            event.target.value = formatDniValue(event.target.value);
        });

        const checkbox = document.getElementById('disabledCheckbox');
        const dateInput = document.getElementById('dateInput');
        const disabledReason = document.getElementById('disabledReason');
        const disabledStartDateInput = document.getElementById('disabledStartDateInput');

        checkbox.addEventListener('change', function() {
            if (this.checked) {
                dateInput.style.display = 'block';
                disabledReason.style.display = 'block';
                disabledStartDateInput.required = true;
            } else {
                dateInput.style.display = 'none';
                disabledReason.style.display = 'none';
                disabledStartDateInput.required = false;
            }
        });

    });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/inputmask.min.js"></script>
@endsection