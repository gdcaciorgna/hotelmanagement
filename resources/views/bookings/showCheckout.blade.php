@extends('layouts.app')
@section('title', 'Checkout para la Reserva')
@section('content')

<div class="bg-light rounded h-100 p-4">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-3"> Checkout: Reserva #{{$booking->id}}</h3>
        </div>
        <div class="col-sm-6 text-end">
            <a href="{{route('bookings.edit', $booking->id)}}" class="btn btn-dark">Volver</a>
        </div>
    </div>

    <div class="row mb-0 mx-1">
        <div class="col-sm-5 border border-secondary p-3">
            <h5> Información de la reserva </h5>
            <div class="row">
                <p class="col-sm-7 pt-0 m-0">Cod. Reserva</p>
                <p class="col-sm-5 pt-0 m-0">#{{$booking->id}}</p>
                <p class="col-sm-7 pt-0 m-0">Fecha de Reserva</p>
                <p class="col-sm-5 pt-0 m-0">{{ Illuminate\Support\Carbon::parse($booking->bookingDate)->format('d/m/Y')}}</p>
                <p class="col-sm-7 pt-0 m-0">Fecha Inicio</p>
                <p class="col-sm-5 pt-0 m-0">{{ Illuminate\Support\Carbon::parse($booking->startDate)->format('d/m/Y')}}</p>
                <p class="col-sm-7 pt-0 m-0">Fecha Fin Pactada</p>
                <p class="col-sm-5 pt-0 m-0">{{ Illuminate\Support\Carbon::parse($booking->agreedEndDate)->format('d/m/Y')}}</p>
                <p class="col-sm-7 pt-0 m-0">Fecha Fin Real</p>
                <p class="col-sm-5 pt-0 m-0">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                <p class="col-sm-7 pt-0 m-0">Cantidad de Huéspedes</p>
                <p class="col-sm-5 pt-0 m-0">{{ $booking->numberOfPeople}}</p>
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
                    <p class="col-sm-4 pt-0 mt-4">Comodidades incluídas</p>
                    <div class="col-sm-8 pt-0 mt-4">
                        @foreach($booking->rate->commodities()->get() as $comm)
                            <li>{{$comm->title}}</li>
                        @endforeach
                    </div>
                </div>
            </div>
           
        </div>
    </div>
    <div class="row mb-0 mx-1">
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
                <p class="col-sm-3 pt-0 m-0">{{$breakdown['actualStayDays']}} 
                <p> 
                    @if($breakdown['actualStayDays'] != $breakdown['agreedStayDays'])
                        (Dias acordados: {{$breakdown['agreedStayDays']}})
                    @endif
                </p>
                </p>
            </div>

        </div>
        <div class="col-sm-7 border border-secondary p-3">
            @if($additionalCommodities->isNotEmpty())
            <h5 class="mx-2 mb-3">Comodidades adicionales contratadas</h5>
            <div class="row mx-2 mb-3">
                <table class="table table-bordered">
                    <thead class="table-secondary" style="font-size: 1.05em;">
                        <tr>
                            <th scope="col">Comodidad</th>
                            <th scope="col">Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($additionalCommodities as $addCom)
                            <tr>
                                <td>{{$addCom->title}}</td>
                                <td>${{number_format($addCom->current_price, 2)}}</td>
                            </tr>
                        @endforeach
                        <!-- Precio Total Comodidades Adicionales -->
                        <tr class="table-secondary fw-bold" style="font-size: 1.05em;">
                            <td class="text-end">Total</td>
                            <td>{{ '$' . number_format($breakdown['bookingCommodities'], 2) }}</td>
                        </tr>
                    </tbody>
                </table>                 
            </div>
            @endif
            @if($additionalServices->isNotEmpty())
            <h5 class="mx-2 mb-3">Servicios adicionales</h5>
            <div class="row mx-2">
                <table class="table table-bordered">
                    <thead class="table-secondary" style="font-size: 1.05em;">
                        <tr>
                            <th scope="col">Fecha</th>
                            <th scope="col">Título</th>
                            <th scope="col">Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($additionalServices as $addSer)
                            <tr>
                                <td>{{ Illuminate\Support\Carbon::parse($addSer->dateTime)->format('d/m/Y')}}</td>
                                <td>{{$addSer->title}}</td>
                                <td>${{number_format($addSer->price, 2)}}</td>
                            </tr>
                        @endforeach
                        <!-- Precio Total Servicios Adicionales -->
                        <tr class="table-secondary fw-bold" style="font-size: 1.05em;">
                            <td colspan="2" class="text-end">Total</td>
                            <td>{{ '$' . number_format($breakdown['bookingAdditionalServices'], 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    @php
    $actualFinalPrice = (
        ($breakdown['basePricePerPersonPerDay'] + $breakdown['basePricePerRatePerDay'] + $breakdown['bookingCommodities']) 
        * $breakdown['numberOfPeople']
        * max($breakdown['actualStayDays'], $breakdown['agreedStayDays']) 
    ) + $breakdown['bookingAdditionalServices'] - $breakdown['returnDepositValue'];
    @endphp
    <div class="row mb-3 mx-1">
        <div class="col-12 border border-secondary p-3">
            <p class="pt-0 m-0">CTR = (PBPD + PTPD + PCA) * p * d + PSA - [VDep]</p>
            <p class="pt-0 m-0">
                <span>Costo total de reserva =</span>
                <span>
                    ({{ '$' . number_format($breakdown['basePricePerPersonPerDay'], 2) }} + 
                    {{ '$' . number_format($breakdown['basePricePerRatePerDay'], 2) }} +  
                    {{ '$' . number_format($breakdown['bookingCommodities'], 2) }}) * 
                    {{ $breakdown['numberOfPeople'] }} * 
                    {{ max($breakdown['actualStayDays'], $breakdown['agreedStayDays']) }} +
                    {{ '$' . number_format($breakdown['bookingAdditionalServices'], 2) }} - 
                    {{ '$' . number_format($breakdown['returnDepositValue'], 2) }}
                </span>
            </p>
            <hr />
            <p class="fs-5">
                <span>Costo total de reserva = </span>  
                <span class="fw-bold" style="color: #333;">{{'$' . number_format($actualFinalPrice)}}</span>     
            </p>
            <hr />
            <div class="">
            <a href="#" id="printScreen" class="btn btn-secondary" onclick="window.print()"><i class="fas fa-print"></i></a>
            <form action="{{ route('bookings.setBookingAsFinished', $booking->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('PUT')
                <input type="hidden" name="actualFinalPrice" value="{{$actualFinalPrice}}">
                <button type="submit" id="saveBookingButton" class="btn btn-primary">Finalizar Reserva</button>
            </form>
            </div>
        </div>
    </div>
</div>

@endsection