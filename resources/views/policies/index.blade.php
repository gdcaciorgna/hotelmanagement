@extends('layouts.app')
@section('title', 'Politicas del Hotel')
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
                            <input type="number" class="form-control MilesNumberFormatted" id="damageDeposit" name="damageDeposit" value="{{ $damageDeposit }}" min="0" max="1000000000" required>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </div>
                    <p class="mt-4">Costo que debe abonar un huésped a la hora de reservar el hospedaje para cubrir posibles roturas o daños. El mismo será devuelto en el momento de entregar la habitación en el checkout, si corresponde.</p>
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
                            <input type="number" class="form-control MilesNumberFormatted" id="basePricePerPersonPerDay" name="basePricePerPersonPerDay" value="{{$basePricePerPersonPerDay}}" min="0" max="1000000000" required>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </div>
                    <p class="mt-4">Este valor será utilizado a la hora de calcular el precio a pagar por el huésped al contratar una estadía. El precio final dependerá, entre otras cosas, de dicho valor, de la cantidad de personas a hospedarse y de la cantidad de días que se alojen en el hotel.</p>
                </form>
            </div>
        </div>
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Horario - Personal de limpieza</h6>
                <form action="{{route('policies.update')}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-4">
                            <input type="time" class="form-control" id="cleaningWorkingHoursFrom" name="cleaningWorkingHoursFrom" value="{{$cleaningWorkingHoursFrom}}" required>
                        </div>
                        <div class="col-4">
                            <input type="time" class="form-control" id="cleaningWorkingHoursTo" name="cleaningWorkingHoursTo" value="{{$cleaningWorkingHoursTo}}" required>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </div>
                    <p class="mt-4">Es el horario en el que estará disponible la posibilidad de solicitar personal de limpieza por parte del personal administrativo.</p>
                </form>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </ul>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>

@endsection
