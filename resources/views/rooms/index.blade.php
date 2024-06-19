@extends('layouts.app')
@section('title', 'Habitaciones')
@section('content')
<div class="container">
    <div class="row g-12 mb-4">
        <div class="row mb-3">
            <div class="col-sm-3">
                <h3 class="mt-2">Habitaciones</h3>
            </div>
            <div class="col-sm-9 text-end">
                <a href="{{route('rooms.create')}}" class="btn btn-dark">Agregar nueva habitación</a>
            </div>
        </div>
    </div>
    <div class="row g-4">
        @foreach ($rooms as $room)
            <div class="col-sm-12 col-xl-4">
                <div class="bg-light rounded h-100 p-4">
                    <div class="row">
                        <div class="col-8">
                            <h6 class="mt-2">Habitación N°: {{$room->code}}</h6>
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn btn-primary">Editar</button>
                        </div>
                    </div>
                    <p class="mt-4">{{$room->description}}</p>
                    <div class="{{$room->getStatusColor()}} text-white text-center py-2 bottom-0 start-0 w-100">{{$room->getStatusFormatted()}}</div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $rooms->links() }}
    </div>
</div>

@endsection
