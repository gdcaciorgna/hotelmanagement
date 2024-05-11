@extends('layouts.app')
@section('title', 'Políticas del hotel')
@section('content')
<div class="container">
    <div class="row g-12 mb-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h3 class="mb-4">Políticas del hotel</h3>
                <p>Aquí se modifican todas las configuraciones generales para el establecimiento de hospedaje.</p>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Depósito por daños</h6>
                <form action="{{route('policies.update')}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-8">
                            <input type="number" class="form-control MilesNumberFormatted" id="damageDeposit" name="damageDeposit" value="{{ $damageDeposit }}" max="1000000000" required>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </div>
                    <p class="mt-4">Costo que debe abonar un huésped a la hora de reservar el hospedaje para cubrir posibles roturas o daños. El mismo será devuelto a en el momento de entregar la habitación en el checkout.</p>
                </form>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Precio base por persona por día</h6>
                <form action="{{route('policies.update')}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-8">
                            <input type="number" class="form-control MilesNumberFormatted" id="basePricePerPersonPerDay" name="basePricePerPersonPerDay" value="{{$basePricePerPersonPerDay}}" max="1000000000" required>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </div>
                    <p class="mt-4">Este valor será utilizado a la hora de calcular el precio a pagar por el huésped al contratar una estadía. El precio final dependerá de dicho valor, la cantidad de personas a hospedarse y la cantidad de días que se alojen en el hotel.</p>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
