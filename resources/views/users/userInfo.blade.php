@extends('layouts.app')
@section('title', 'Agregar o editar usuario')
@section('content')

@php
    if($action == 'edit'){
        $headerText = "Editar Usuario: #{$user->id}";
        $formAction = route('users.update', ['id' => $user->id]);
        $method = 'PUT';
    }
    else{
        $headerText = "Agregar nuevo usuario";
        $formAction = route('users.store');
        $method = 'POST';
    }
@endphp

<div class="bg-light rounded h-100 p-4">
    <div class="row mb-3">
        <div class="col-sm-3">
            <h6 class="mb-4"> {{ $headerText}} </h6>
        </div>
        <div class="col-sm-9 text-end">
            <a href="{{route('users.index')}}" class="btn btn-dark">Ver usuarios</a>
        </div>
    </div>
   
    <form action="{{$formAction}}" method="POST">
        @csrf
        @if(isset($method))
            @method($method)
        @endif
        <fieldset class="row mb-3">
            <legend class="col-form-label col-sm-3 pt-0">Tipo Doc</legend>
            <div class="col-sm-9">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="docType" value="DNI"
                        @if($action == 'create' || old('docType', isset($user) ? $user->docType : '') == 'DNI') checked @endif id="gridDNI">
                    <label class="form-check-label" for="gridDNI">
                        DNI
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="docType" value="PAS"
                        @if(old('docType', isset($user) ? $user->docType : '') == 'PAS') checked @endif id="gridPAS">
                    <label class="form-check-label" for="gridPAS">
                        Pasaporte
                    </label>
                </div>
            </div>
        </fieldset>
        <div class="row mb-3">
            <label for="numDoc" class="col-sm-3 col-form-label">Nro Doc</label>
            <div class="col-sm-9">
                <input type="text" class="form-control @error('numDoc') is-invalid @enderror" id="numDoc" name="numDoc" data-bs-decimals="0" data-bs-step="1" placeholder="12.345.678" value="{{ old('numDoc', $user->numDoc ?? '')  }}">
                @error('numDoc')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror    
            </div>
        </div>
        <div class="row mb-3">
            <label for="name" class="col-sm-3 col-form-label">Nombre y apellido</label>
            <div class="col-sm-4">
                <input type="text" class="form-control @error('firstName') is-invalid @enderror" id="firstName" name="firstName" placeholder="Nombre" value="{{ old('firstName', $user->firstName ?? '')  }}">
                @error('firstName')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror    
            </div>
            <div class="col-sm-5">
                <input type="text" class="form-control @error('lastName') is-invalid @enderror" id="lastName" name="lastName" placeholder="Apellido" value="{{ old('lastName', $user->lastName ?? '')  }}">
                @error('lastName')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror    
            </div>
        </div>
        <div class="row mb-3">
            <label for="name" class="col-sm-3 col-form-label">Correo electrónico</label>
            <div class="col-sm-9">
                <input type="email" class="form-control" id="email" name="email" placeholder="juanperez@gmail.com" value="{{ old('email', $user->email ?? '')  }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <label for="phone" class="col-sm-3 col-form-label">Teléfono</label>
            <div class="col-sm-9">
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="54 9 3462 634739" value="{{ old('phone', $user->phone ?? '')  }}" required data-intl-tel-input="true">
            </div>
        </div>        
        <div class="row mb-3">
            <label for="name" class="col-sm-3 col-form-label">Dirección</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="address" name="address" placeholder="219 Calle A, Rosario, Santa Fe" value="{{ old('address', $user->address ?? '')  }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <label for="fechaNacimiento" class="col-sm-3 col-form-label">Fecha de Nacimiento:</label>
            <div class="col-sm-9">
                <input type="date" class="form-control @error('bornDate') is-invalid @enderror" id="bornDate" name="bornDate"
                @if(old('bornDate')) 
                   value="{{ \Carbon\Carbon::parse(old('bornDate'))->format('Y-m-d') }}" 
                @elseif(isset($user) && $user->bornDate) 
                   value="{{ \Carbon\Carbon::parse($user->bornDate)->format('Y-m-d') }}" 
                @endif
               >
               @error('bornDate')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror  
            </div>
          
        </div>
                             
        <div class="row mb-3">
            <label for="userType" class="col-sm-3 col-form-label">Tipo Usuario</label>
            <div class="col-sm-9">
                <select class="form-select" id="userType" name="userType">
                    <option value="Receptionist" 
                        {{ (old('userType', isset($user) ? $user->userType : '') == 'Receptionist') ? 'selected' : '' }}>
                        Recepcionista / Admin
                    </option>
                    <option value="Cleaner" 
                        {{ (old('userType', isset($user) ? $user->userType : '') == 'Cleaner') ? 'selected' : '' }}>
                        Empleado de limpieza
                    </option>
                    <option value="Guest" 
                        {{ (old('userType', isset($user) ? $user->userType : '') == 'Guest' || $action != 'edit') ? 'selected' : '' }}>
                        Huésped
                    </option>
                </select>
            </div>
        </div>        
        <div class="workInformation">
            <div class="row mb-3">
                <label for="weekdayStartWorkHours" class="col-sm-3 col-form-label">Horario Laboral</label>
                <div class="col-sm-4">
                    <input type="time" class="form-control" id="weekdayStartWorkHours" name="weekdayStartWorkHours"
                    @if(old('weekdayStartWorkHours')) 
                    value="{{ \Carbon\Carbon::parse(old('weekdayStartWorkHours'))->format('H:i') }}" 
                    @elseif(isset($user) && $user->weekdayStartWorkHours) 
                    value="{{ \Carbon\Carbon::parse($user->weekdayStartWorkHours)->format('H:i') }}" 
                    @endif
                >
                </div>
                <div class="col-sm-5">
                    <input type="time" class="form-control" id="weekdayEndWorkHours" name="weekdayEndWorkHours"
                    @if(old('weekdayEndWorkHours')) 
                    value="{{ \Carbon\Carbon::parse(old('weekdayEndWorkHours'))->format('H:i') }}" 
                    @elseif(isset($user) && $user->weekdayEndWorkHours) 
                    value="{{ \Carbon\Carbon::parse($user->weekdayEndWorkHours)->format('H:i') }}" 
                    @endif
                >
                </div>
            </div>
            <div class="row mb-3">
                <label for="fechaNacimiento" class="col-sm-3 col-form-label">Inicio de actividad laboral</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control" id="startEmploymentDate" name="startEmploymentDate"
                    @if(old('startEmploymentDate')) 
                    value="{{ \Carbon\Carbon::parse(old('startEmploymentDate'))->format('Y-m-d') }}" 
                    @elseif(isset($user) && $user->startEmploymentDate) 
                    value="{{ \Carbon\Carbon::parse($user->startEmploymentDate)->format('Y-m-d') }}" 
                    @endif
                >
                </div>
            </div>      
        </div>
                
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
            @if($action == 'edit')
                <div class="col-sm-3">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        Modificar contraseña
                    </button>
                </div>
            @else
                <div class="col-sm-3"></div>
            @endif

            <div class="col-sm-9 text-end">
                    <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
</div>
  
<!-- Change password modal -->
<form action="{{ route('users.setNewPassword') }}" method="POST" id="changePasswordForm">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="changePasswordModalLabel">Modificar contraseña</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="newPassword" class="col-sm-4 col-form-label">Nueva contraseña</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="newPassword" name="newPassword">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="newPassword_confirmation" class="col-sm-4 col-form-label">Repetir contraseña</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="newPassword_confirmation" name="newPassword_confirmation">
                        </div>
                    </div>
                    <div id="passwordError" class="text-danger" style="display: none;"></div>
                    <div id="passwordSuccess" class="text-success" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const numDocInput = document.getElementById('numDoc');
    
        function formatNumDocValue(value) {
            return value.replace(/\D/g, '').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }

        numDocInput.value = formatNumDocValue(numDocInput.value);

        numDocInput.addEventListener('input', function(event) {
            event.target.value = formatNumDocValue(event.target.value);
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

        action = "{{$action}}";
        if(action == 'edit'){
            document.getElementById("changePasswordForm").addEventListener("submit", function(event) {
                event.preventDefault();
                var newPassword = document.getElementById("newPassword").value;
                var confirmPassword = document.getElementById("newPassword_confirmation").value;
                var userId = "{{ isset($user) ? $user->id : '' }}";
                
                var formData = new FormData(this);
                formData.append('newPassword', newPassword);
                formData.append('newPassword_confirmation', confirmPassword);
                formData.append('user_id', userId);
                
                $.ajax({
                    url: "{{ route('users.setNewPassword') }}",
                    method: "POST",
                    data: formData,
                    processData: false, 
                    contentType: false,                 
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success == 'true') {
                            $('#passwordSuccess').text('Contraseña actualizada correctamente').show();
                            $('#passwordError').hide();
                        } else {
                            $('#passwordError').text(response.message).show();
                            $('#passwordSuccess').hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        }
    });

    const userTypeDropdown = document.getElementById('userType');
        const workInformation = document.querySelector('.workInformation');

        function toggleWorkInformationDisplay() {
            const userTypeValue = userTypeDropdown.value;
            if (userTypeValue === 'Guest') {
                workInformation.style.display = 'none';
            } else {
                workInformation.style.display = 'block';
            }
        }

        toggleWorkInformationDisplay();

        userTypeDropdown.addEventListener('change', function() {
            toggleWorkInformationDisplay();
        });


</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/inputmask.min.js"></script>

@endsection