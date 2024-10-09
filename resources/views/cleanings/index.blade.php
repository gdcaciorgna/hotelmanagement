@extends('layouts.app')
@section('title', 'Limpiezas')
@section('content')
<div class="container">
    <div class="row g-12 mb-4">
        <div class="row mb-2">
            <div class="col-12">
                <h3>Limpiezas</h3>
            </div>
        </div>
    </div>
    <div class="row g-4">
        @foreach ($cleanings as $cleaning)
            <div class="col-sm-12 col-xl-6 justify-content-start">
                <div class="bg-light rounded h-100 p-4 d-flex flex-column justify-content-start">
                    <div class="row">
                        <div class="col-7">
                            <span class="text-secondary small">Limpieza #{{$cleaning->id}}</span>
                            <h5 class="mt-1">Nro. Habitación: {{$cleaning->room->code}}</h5>
                        </div>
                        <div class="col-5 text-end">
                            <a href="{{route('cleanings.startCleaningAsCleaner', $cleaning->id)}}" type="submit" class="btn btn-primary btn-large">Iniciar limpieza</a>
                        </div>
                    </div>
                    <p class="m-0">Solicitado: <strong>{{ \Carbon\Carbon::parse($cleaning->requestedDateTime)->format('d/m/Y') }}</strong></p>

                    @if(Auth::user()->userType == 'Receptionist')
                        <p class="m-0">Empleado asignado: <strong>{{$cleaning->user->fullname}}</strong></p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $cleanings->links() }}
    </div>
</div>

@endsection
