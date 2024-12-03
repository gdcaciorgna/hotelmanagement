@extends('layouts.app')
@section('title', 'Inicio - Sistema de hotelería')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                @if (auth()->user())
                    <div class="card-header">Mi Perfil</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="container">
                            <div class="main-body">
                                    <!-- Breadcrumb -->
                                    <nav aria-label="breadcrumb" class="main-breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">Inicio</li>
                                        <li class="breadcrumb-item">Mi perfil</li>
                                    </ol>
                                    </nav>
                                    <!-- /Breadcrumb -->
                                    <div class="row gutters-sm">
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex flex-column align-items-center text-center">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                                                <div class="mt-3">
                                                    <h4>{{Auth::user()->fullName}}</h4>
                                                    <p class="text-secondary mb-1">{{Auth::user()->getUserTypeFormatted()}}</p>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="row">
                                                <div class="col-sm-4">
                                                    <h6 class="mb-0">Nombre completo</h6>
                                                </div>
                                                <div class="col-sm-8 text-secondary">
                                                    {{Auth::user()->fullName}}
                                                </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <h6 class="mb-0">Tipo de Usuario</h6>
                                                    </div>
                                                    <div class="col-sm-8 text-secondary">
                                                        {{Auth::user()->getUserTypeFormatted()}}
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <h6 class="mb-0">Email</h6>
                                                    </div>
                                                    <div class="col-sm-8 text-secondary">
                                                        {{Auth::user()->email}}
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <h6 class="mb-0">Fecha Nacimiento</h6>
                                                    </div>
                                                    <div class="col-sm-8 text-secondary">
                                                        {{ Illuminate\Support\Carbon::parse(Auth::user()->bornDate)->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <h6 class="mb-0">Teléfono</h6>
                                                    </div>
                                                    <div class="col-sm-8 text-secondary">
                                                        {{Auth::user()->getFormattedPhoneAttribute()}}
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <h6 class="mb-0">Dirección</h6>
                                                    </div>
                                                    <div class="col-sm-8 text-secondary">
                                                        {{Auth::user()->address}}
                                                    </div>
                                                </div>
                                                <hr>
                                            
                                                <div class="row">
                                                <div class="col-sm-4">
                                                    <h6 class="mb-0">Estado</h6>
                                                </div>
                                                <div class="col-sm-8 text-secondary">
                                                    @if (Auth::user()->status == 1)
                                                        <button type="button" class="btn btn-success btn-sm">Habilitado</button>                                                   
                                                    @else
                                                        <button type="button" class="btn btn-danger btn-sm">Inhabilitado</button>  
                                                        @if (isset(Auth::user()->disabledStartDate) && !empty(Auth::user()->disabledStartDate))
                                                            <h6 class="mt-3">Fecha Inhabilitación:</h6>
                                                            <span>{{Illuminate\Support\Carbon::parse(Auth::user()->disabledStartDate)->format('d/m/Y') }}</span>
                                                        @endif     
                                                        @if (isset(Auth::user()->disabledReason) && !empty(Auth::user()->disabledReason))
                                                            <h6 class="mt-3">Motivo Inhabilitación:</h6>
                                                            <span>{{Auth::user()->disabledReason}}</span>
                                                        @endif                                                                                                                            
                                                    @endif
                                                </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                <div class="col-sm-12">
                                                </div>
                                                </div>
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card-header">Escritorio</div>
                    <div class="card-body">
                        <div class="container mb-4">
                            <div class="card">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">¡Ups! No estás logueado</h5>
                                        <p class="card-text">Para poder visualizar la información de todas tus reservas, conocer tus comodidades y solicitar la limpieza de tu habitación, debés iniciar sesión con el usuario asignado por recepción</p>
                                    </div>
                                    <a href="{{ route('login') }}" class="btn btn-primary w-50">Iniciar sesión</a>
                                </div>
                            </div>                                                          
                        </div>
                        <div class="container">
                            <h2 class="mt-4 mb-2">Todas las comodidades</h2>
                            <div class="main-body">                      
                                <div class="row g-4">
                                    @foreach ($commodities as $commodity)
                                        <div class="col-sm-12 col-xl-4 justify-content-start">
                                            <div class="bg-light rounded h-100 p-4 d-flex flex-column justify-content-start">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <span class="text-secondary small">#{{$commodity->id}}</span>
                                                        <h5 class="mt-1">{{$commodity->title}}</h5>
                                                    </div>
                                                    <div class="col-3">
                                                    </div>
                                                </div>
                                                <p class="mt-2">{{ \Illuminate\Support\Str::limit($commodity->description, 150, '...') }}</p>
                                                <p class="mt-2">Precio: <strong>${{$commodity->currentPrice}}</strong></p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $commodities->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
