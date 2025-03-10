@extends('layouts.app')
@section('content')

@php
    if($action == 'edit'){
        $title = 'Editar Habitacion';
        $headerText = "Editar Habitación: #{$room->id}";
        $formAction = route('rooms.update', ['id' => $room->id]);
        $method = 'PUT';
        $saveButtonText = 'Actualizar';
    }
    else{
        $title = 'Agregar Habitacion';
        $headerText = "Agregar nueva habitación";
        $formAction = route('rooms.store');
        $method = 'POST';
        $saveButtonText = 'Publicar';
        
    }
@endphp
    
@section('title', $title)

<div class="bg-light rounded h-100 p-4">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-sm-3">
            <h6 class="mb-4"> {{ $headerText}} </h6>
        </div>
        <div class="col-sm-9 text-end">
            <a href="{{route('rooms.index')}}" class="btn btn-dark">Ver habitaciones</a>
        </div>
    </div>
   
    <form action="{{$formAction}}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($method))
            @method($method)
        @endif

        <div class="row mb-3">
            <label for="code" class="col-sm-3 col-form-label">Nro Habitación</label>
            <div class="col-sm-9">
                <input type="number" class="form-control @error('code') is-invalid @enderror" id="code" name="code" placeholder="101" value="{{ old('code', $room->code ?? '') }}">   
            </div>
        </div>
        <div class="row mb-3">
            <label for="maxOfGuests" class="col-sm-3 col-form-label">Cant. máxima de huéspedes</label>
            <div class="col-sm-9">
                <input type="number" class="form-control @error('maxOfGuests') is-invalid @enderror" id="maxOfGuests" name="maxOfGuests" placeholder="2" value="{{ old('maxOfGuests', $room->maxOfGuests ?? '')  }}">
            </div>
        </div>
        <div class="row mb-3">
            <label for="description" class="col-sm-3 col-form-label">Descripción</label>
            <div class="col-sm-9">
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Escribe aquí la descripción...">{{ old('description', $room->description ?? '')}}</textarea>
            </div>
        </div>
        <div class="row mb-3">
            <label for="image" class="col-sm-3 col-form-label">Imagen</label>
            <div class="col-sm-9">

                @if(isset($room) && $room->image)
                    <div class="mb-2">
                        <img src="{{ asset('/img/rooms/' . $room->image) }}" alt="Imagen actual" class="img-thumbnail" style="max-height: 150px;">
                        <p class="mt-2">{{ $room->image }}</p>
                            <label class="form-check-label">
                            <input type="checkbox" name="delete_image" value="1"> Eliminar imagen actual
                        </label>
                    </div>
                @endif
                <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/*"> 
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary">{{ $saveButtonText }}</button>
                @if($action == 'edit')
                    <button type="button" class="btn btn-link text-danger p-0 ms-2" data-bs-toggle="modal" data-bs-target="#deleteModal">Eliminar</button>
                @endif
            </div>
        </div>
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
@if($action == 'edit')
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta habitación?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{route('rooms.destroy', $room->id)}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar Habitación</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection