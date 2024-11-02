@extends('layouts.app')
@section('title', 'Limpiezas')
@section('content')

<div class="container">
    <div class="row g-12 mb-4">
        <div class="row mb-2">
            <div class="col-12">
                <h3> 
                    @if(Auth::user()->userType == 'Cleaner')
                        Mis limpiezas pendientes
                    @else   
                        Limpiezas pendientes
                    @endif
                </h3>
            </div>
        </div>
    </div>
    <div class="row g-4">
        @foreach ($activeCleanings as $cleaning)
            @php 
                $btnText = "Iniciar limpieza";
                $btnStyle = "btn-primary";
                $action = "cleanings.startCleaningAsCleaner";
                if($cleaning->room->status == 'Cleaning in process'){
                    $btnText = "Finalizar limpieza";
                    $btnStyle = "btn-danger";   
                    $action = "cleanings.finishCleaningAsCleaner"; 
                }
            @endphp
            <div class="col-sm-12 col-xl-6 justify-content-start">
                <div class="bg-light rounded h-100 p-4 d-flex flex-column justify-content-start">
                    <div class="row">
                        <div class="col-7">
                            <span class="text-secondary small">Limpieza #{{$cleaning->id}}</span>
                            <h5 class="mt-1">Nro. Habitación: {{$cleaning->room->code}}</h5>
                        </div>
                        <div class="col-5 text-end">
                            <form action="{{ route($action, $cleaning->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn {{$btnStyle}} btn-large">{{$btnText}}</button>
                            </form>    
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
        {{ $activeCleanings->links() }}
    </div>
</div>

@endsection
