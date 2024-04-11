@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">
        @if($action == 'edit')
            Editar Usuario: #{{$userId}}
        @elseif($action == 'create')
            Agregar nuevo usuario
        @endif
    </h6>
    <form>
        <div class="row mb-3">
            <label for="dni" class="col-sm-3 col-form-label">DNI</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="dni" name="dni" data-bs-decimals="0" data-bs-step="1" placeholder="12.345.678" required>
            </div>
        </div>
        <div class="row mb-3">
            <label for="name" class="col-sm-3 col-form-label">Nombre y apellido</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="name" name="name" placeholder="Juan Carlos Pérez" required>
            </div>
        </div>
        <div class="row mb-3">
            <label for="name" class="col-sm-3 col-form-label">Correo electrónico</label>
            <div class="col-sm-9">
                <input type="email" class="form-control" id="email" name="email" placeholder="juanperez@gmail.com" required>
            </div>
        </div>
        <div class="row mb-3">
            <label for="fechaNacimiento" class="col-sm-3 col-form-label">Fecha de Nacimiento:</label>
            <div class="col-sm-9">
                <input type="date" class="form-control" id="disabledStartDate" name="disabledStartDate">
            </div>
        </div>
                           
        <fieldset class="row mb-3">
            <legend class="col-form-label col-sm-3 pt-0">Tipo Usuario</legend>
            <div class="col-sm-9">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="radioUserType"
                        id="gridReceptionist" value="Receptionist">
                    <label class="form-check-label" for="gridReceptionist">
                        Recepcionista / Admin
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="radioUserType"
                        id="gridCleaner" value="Cleaner">
                    <label class="form-check-label" for="gridCleaner">
                        Empleado de limpieza
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="radioUserType"
                        id="gridGuest" value="Guest" checked>
                    <label class="form-check-label" for="gridGuest">
                        Huésped
                    </label>
                </div>
            </div>
        </fieldset>
        <div class="row mb-3">
            <legend class="col-form-label col-sm-3 pt-0">Usuario Inhabilitado</legend>
            <div class="col-sm-9">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="disabled" id="disabledCheckbox">
                    <label class="form-check-label" for="disabledCheckbox">
                        Inhabilitar Usuario
                    </label>
                </div>
                <div class="mt-3" id="dateInput" style="display: none;">
                    <label for="disabledStartDate" class="form-label">Fecha de Inhabilitación:</label>
                    <input type="date" class="form-control" id="disabledStartDate" name="disabledStartDate">
                </div>
                <div class="mt-3" id="disabledReason" style="display: none;">
                    <label for="disabledReasonTextarea" class="form-label">Motivo de Inhabilitación:</label>
                    <textarea class="form-control" id="disabledReasonTextarea" name="disabledReason"></textarea>
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
        
        dniInput.addEventListener('input', function(event) {
            let value = event.target.value.replace(/\D/g, ''); 
            const formattedValue = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            
            dniInput.value = formattedValue;
        });
    });

    const checkbox = document.getElementById('disabledCheckbox');
    const dateInput = document.getElementById('dateInput');
    const disabledReason = document.getElementById('disabledReason');

    checkbox.addEventListener('change', function() {
        if (this.checked) {
            dateInput.style.display = 'block';
            disabledReason.style.display = 'block';

        } else {
            dateInput.style.display = 'none';
            disabledReason.style.display = 'none';
        }
    });


    //Date format validation (dd/mm/yyyy)
    const bornDateDayInput = document.getElementById('bornDateDay');
    const bornDateMonthInput = document.getElementById('bornDateMonth');
    const bornDateYearInput = document.getElementById('bornDateYear');

    bornDateDayInput.addEventListener('input', function() {
        if (this.value.length > 2) {
            this.value = this.value.slice(0, 2);
        }
    });

    bornDateMonthInput.addEventListener('input', function() {
        if (this.value.length > 2) {
            this.value = this.value.slice(0, 2);
        }
    });

    bornDateYearInput.addEventListener('input', function() {
        if (this.value.length > 4) {
            this.value = this.value.slice(0, 4);
        }
    });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/inputmask.min.js"></script>
@endsection