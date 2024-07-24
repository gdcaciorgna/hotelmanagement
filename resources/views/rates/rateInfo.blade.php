@extends('layouts.app')
@section('title', 'Agregar o editar tarifa')
@section('content')

@php
    if($action == 'edit'){
        $headerText = "Editar tarifa: #{$commodity->id}";
        $formAction = route('commodities.update', ['id' => $commodity->id]);
        $method = 'PUT';
        $saveButtonText = 'Actualizar';
    }
    else{
        $headerText = "Agregar nueva tarifa";
        $formAction = route('commodities.store');
        $method = 'POST';
        $saveButtonText = 'Publicar';

    }
@endphp

<div class="bg-light rounded h-100 p-4">
    <div class="row mb-3">
        <div class="col-sm-3">
            <h6 class="mb-4"> {{ $headerText}} </h6>
        </div>
        <div class="col-sm-9 text-end">
            <a href="{{route('commodities.index')}}" class="btn btn-dark">Ver tarifas</a>
        </div>
    </div>
   
    <form action="{{$formAction}}" method="POST">
        @csrf
        @if(isset($method))
            @method($method)
        @endif

        <div class="row mb-3">
            <label for="title" class="col-sm-3 col-form-label">Título</label>
            <div class="col-sm-9">
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Pileta climatizada" value="{{ old('title', $commodity->title ?? '')  }}">
                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror    
            </div>
        </div>

        <div class="row mb-3">
            <label for="description" class="col-sm-3 col-form-label">Descripción</label>
            <div class="col-sm-9">
                <textarea style="min-height: 100px;" class="form-control" id="description" name="description" placeholder="Escribe aquí la descripción...">{{ old('description', $commodity->description ?? '')}}</textarea>
                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror    
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary">{{ $saveButtonText }}</button>
                @if($action == 'edit')
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Eliminar</button>
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
                    ¿Estás seguro de que deseas eliminar esta tarifa?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{route('commodities.destroy', $commodity->id)}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar tarifa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection