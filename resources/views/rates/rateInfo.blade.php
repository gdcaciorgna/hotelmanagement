@extends('layouts.app')
@section('title', 'Agregar o editar tarifa')
@section('content')

@php
    if($action == 'edit'){
        $headerText = "Editar tarifa: #{$rate->id}";
        $formAction = route('rates.update', ['id' => $rate->id]);
        $method = 'PUT';
        $saveButtonText = 'Actualizar';
    }
    else{
        $headerText = "Agregar nueva tarifa";
        $formAction = route('rates.store');
        $method = 'POST';
        $saveButtonText = 'Publicar';

    }
@endphp

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
            <a href="{{route('rates.index')}}" class="btn btn-dark">Ver tarifas</a>
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
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Ultra premium" value="{{ old('title', $rate->title ?? '')  }}">
                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror    
            </div>
        </div>

        <div class="row mb-3">
            <label for="currentPrice" class="col-sm-3 col-form-label">Precio x persona x día</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <div class="input-group-text">$</div>
                    <input type="number" step="0.01" class="form-control @error('currentPrice') is-invalid @enderror" id="currentPrice" name="currentPrice" placeholder="190" value="{{ old('currentPrice', $rate->currentPrice ?? '')  }}">
                </div>
                @error('currentPrice')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror    
            </div>
        </div>

        <div class="row mb-3">
            <label for="description" class="col-sm-3 col-form-label">Descripción</label>
            <div class="col-sm-9">
                <textarea style="min-height: 100px;" class="form-control" id="description" name="description" placeholder="Escribe aquí la descripción...">{{ old('description', $rate->description ?? '')}}</textarea>
                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror    
            </div>
        </div>
        <div class="row mb-3">
            <label for="commodities" class="col-sm-3 col-form-label">Comodidades incluídas</label>
            <div class="col-sm-9">
                @foreach ($commodities as $commodity)
                    <input class="form-check-input" type="checkbox" name="commodities[]" id="commodityCheckbox{{$commodity->id}}" value="{{ $commodity->id }}"
                        @if ($action=='edit' && ($rate->commodities->contains($commodity->id) || old('commodities') && in_array($commodity->id, old('commodities'))))
                            checked
                        @endif
                    >
                    <label class="form-check-label" for="commodityCheckbox">
                        {{$commodity->title}}
                    </label>
                    <br>
                @endforeach               
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
                    <form action="{{route('rates.destroy', $rate->id)}}" method="POST">
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