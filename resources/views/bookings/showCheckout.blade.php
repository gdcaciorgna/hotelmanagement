@extends('layouts.app')
@section('title', 'Checkout - Reserva #' . $booking->id)
@section('content')

<div class="bg-light rounded h-100 p-4">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h2 class="mb-4"> Checkout: Reserva #{{$booking->id}} </h2>
        </div>
        <div class="col-sm-6 text-end">
            <a href="{{route('bookings.edit', $booking->id)}}" class="btn btn-dark">Volver</a>
        </div>
    </div>

    <div class="row mb-0">
        <div class="col-sm-5 border border-secondary p-3">
            <h5 class="m-3"> Información de la reserva </h5>
            <div class="row m-3">
                <p class="col-sm-7 pt-0 m-0">Cód. Reserva</p>
                <p class="col-sm-5 pt-0 m-0">#{{$booking->id}}</p>
                <p class="col-sm-7 pt-0 m-0">Fecha de reserva</p>
                <p class="col-sm-5 pt-0 m-0">{{ Illuminate\Support\Carbon::parse($booking->bookingDate)->format('d/m/Y')}}</p>
                <p class="col-sm-7 pt-0 m-0">Fecha inicio</p>
                <p class="col-sm-5 pt-0 m-0">{{ Illuminate\Support\Carbon::parse($booking->startDate)->format('d/m/Y')}}</p>
                <p class="col-sm-7 pt-0 m-0">Fecha fin pactada</p>
                <p class="col-sm-5 pt-0 m-0">{{ Illuminate\Support\Carbon::parse($booking->agreedEndDate)->format('d/m/Y')}}</p>
                <p class="col-sm-7 pt-0 m-0">Cantidad de huéspedes</p>
                <p class="col-sm-5 pt-0 m-0">{{ Illuminate\Support\Carbon::parse($booking->numberOfPeople)->format('d/m/Y')}}</p>
                <p class="col-sm-7 pt-0 m-0">Devolver depósito</p>
                <p class="col-sm-5 pt-0 m-0">{{$booking->getReturnDepositText()}}</p>
            </div>
        </div>
        <div class="col-sm-7 border border-secondary p-3">
            <div class="row mb-3">
                <h5>Habitación</h5>
                <div class="row">
                    <p class="col-sm-4 pt-0 m-0">Nro. Habitación</p>
                    <p class="col-sm-8 pt-0 m-0">#{{$booking->room->code}}</p>
                    <p class="col-sm-4 pt-0 m-0">Cant. Máxima de huéspedes</p>
                    <p class="col-sm-8 pt-0 m-0">{{$booking->room->maxOfGuests}}</p>
                    <p class="col-sm-4 pt-0 m-0">Descripción</p>
                    <p class="col-sm-8 pt-0 m-0">{{$booking->room->description}}</p>
                </div>
            </div>
            <div class="row mb-3">
                <h5>Tarifa</h5>
                <div class="row">
                    <p class="col-sm-4 pt-0 m-0">ID</p>
                    <p class="col-sm-8 pt-0 m-0">#{{$booking->rate->id}}</p>
                    <p class="col-sm-4 pt-0 m-0">Título</p>
                    <p class="col-sm-8 pt-0 m-0">{{$booking->rate->title}}</p>
                    <p class="col-sm-4 pt-0 m-0">Descripción</p>
                    <p class="col-sm-8 pt-0 m-0">{{$booking->rate->description}}</p>
                </div>
            </div>
           
        </div>
    </div>
    <div class="row mb-0">
        <div class="col-sm-5 border border-secondary p-3">
            <h5 class="mb-2"> Costo de la estadía</h5>
            <div class="row">
                <p class="col-sm-9 pt-0 m-0">[VDep] - Devolución depósito</p>
                <p class="col-sm-3 pt-0 m-0">{{ '- $' . number_format($breakdown['returnDepositValue'], 2) }}</p>
            </div>
            <div class="row">
                <p class="col-sm-9 pt-0 m-0">[PBPD] - Precio persona por día</p>
                <p class="col-sm-3 pt-0 m-0">{{ '$' . number_format($breakdown['basePricePerPersonPerDay'], 2) }}</p>
            </div>
            <div class="row">
                <p class="col-sm-9 pt-0 m-0">[PTPD] - Precio tarifa por día</p>
                <p class="col-sm-3 pt-0 m-0">{{ '$' . number_format($breakdown['basePricePerRatePerDay'], 2) }}</p>
            </div>
            <div class="row">
                <p class="col-sm-9 pt-0 m-0">[PCA] - Precio comodidades adicionales</p>
                <p class="col-sm-3 pt-0 m-0">{{ '$' . number_format($breakdown['bookingCommodities'], 2) }}</p>
            </div>
            <div class="row">
                <p class="col-sm-9 pt-0 m-0">[PSA] - Precio total servicios adicionales</p>
                <p class="col-sm-3 pt-0 m-0">{{ '$' . number_format($breakdown['bookingAdditionalServices'], 2) }}</p>
            </div>
            <div class="row">
                <p class="col-sm-9 pt-0 m-0">[p] - Cantidad de personas</p>
                <p class="col-sm-3 pt-0 m-0">{{$breakdown['numberOfPeople']}}</p>
            </div>
            <div class="row">
                <p class="col-sm-9 pt-0 m-0">[d] - Cantidad de días</p>
                <p class="col-sm-3 pt-0 m-0">{{$breakdown['stayDays']}}</p>
            </div>

        </div>
        <div class="col-sm-7 border border-secondary p-3">
            <div class="row m-3">
                <h5>Comodidades adicionales contratadas</h5>
                <table class="table table-bordered">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col">Comodidad</th>
                            <th scope="col">Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Comodidad #1</td>
                            <td>$80</td>
                        </tr>
                        <tr>
                            <td>Comodidad #2</td>
                            <td>$25</td>
                        </tr>
                    </tbody>
                </table>            
            </div>
            <div class="row m-3">
                <h5>Servicios adicionales</h5>
                <table class="table table-bordered">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col">Fecha</th>
                            <th scope="col">Título</th>
                            <th scope="col">Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>20/08/2024</td>
                            <td>Coca Cola 1L</td>
                            <td>$8.25</td>
                        </tr>
                        <tr>
                            <td>25/04/2024</td>
                            <td>Café Cortado</td>
                            <td>$25</td>
                        </tr>
                    </tbody>
                </table>                 
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12 border border-secondary p-3">
            <p class="pt-0 m-0">CTR = (PBPD + PTPD + PCA) * p * d + PSA - [VDep]</p>
            <p class="pt-0 m-0">
                <span>Costo total de reserva =</span>
                <span>({{ '$' . number_format($breakdown['basePricePerPersonPerDay'], 2) }} + {{ '$' . number_format($breakdown['basePricePerRatePerDay'], 2)}} +  {{ '$' . number_format($breakdown['bookingCommodities'], 2)}} ) * {{$breakdown['stayDays']}} * {{$breakdown['numberOfPeople']}} + {{ '$' . number_format($breakdown['bookingAdditionalServices'], 2)}} - {{ '$' . number_format($breakdown['returnDepositValue'], 2)}}</span>
            </p>
            <p>
                <span>Costo total de reserva = </span>  
                <span><strong>{{$booking->calculatedPrice()}}</strong></span>     
            </p>
        </div>
    </div>
</div>

@endsection