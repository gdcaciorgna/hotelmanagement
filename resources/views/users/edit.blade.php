@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">
        @if($action == 'edit')
            Edit User: #{{$userId}}
        @elseif($action == 'create')
            Create User
        @endif
    </h6>
    <form>
        <div class="row mb-3">
            <label for="dni" class="col-sm-2 col-form-label">DNI</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="dni" data-bs-decimals="0" data-bs-step="1" placeholder="12.345.678" >
            </div>
        </div>
        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Nombre y apellido</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" placeholder="Juan Carlos Pérez" >
            </div>
        </div>

        <div class="row mb-3">
            <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="fechaNacimiento" placeholder="DDMMYYYY">
            </div>
        </div>

        <div class="row mb-3">
            <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPassword3">
            </div>
        </div>
        <fieldset class="row mb-3">
            <legend class="col-form-label col-sm-2 pt-0">Radios</legend>
            <div class="col-sm-10">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gridRadios"
                        id="gridRadios1" value="option1" checked>
                    <label class="form-check-label" for="gridRadios1">
                        First radio
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gridRadios"
                        id="gridRadios2" value="option2">
                    <label class="form-check-label" for="gridRadios2">
                        Second radio
                    </label>
                </div>
            </div>
        </fieldset>
        <div class="row mb-3">
            <legend class="col-form-label col-sm-2 pt-0">Checkbox</legend>
            <div class="col-sm-10">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="gridCheck1">
                    <label class="form-check-label" for="gridCheck1">
                        Check me out
                    </label>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Sign in</button>
    </form>
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
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/inputmask.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Inputmask('99/99/9999', {
            placeholder: "DDMMYYYY",
            clearIncomplete: true
        }).mask("#fechaNacimiento");
    });
</script>

@endsection