@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">Usuarios</h6>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">DNI</th>
                    <th scope="col">Nombre y apellido</th>
                    <th scope="col">Tipo Usuario</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>12.345.678</td>
                    <td>Juan Pérez</td>
                    <td>Recepcionista</td>
                    <td><span class="btn btn-sm btn-sm-square btn-outline-success m-2"><i class="fa fa-check"></i></span></td>
                    <td><a href="#" type="button" class="btn btn-sm btn-sm-square btn-outline-primary m-2"><i class="fa fa-edit"></i></a> <a href="#" type="button" class="btn btn-sm btn-sm-square btn-outline-danger m-2"><i class="fa fa-trash-alt"></i></a></td>
                </tr>
                <tr>
                    <th scope="row">1</th>
                    <td>12.345.678</td>
                    <td>Juan Pérez</td>
                    <td>Recepcionista</td>
                    <td><span class="btn btn-sm btn-sm-square btn-outline-danger m-2"><i class="fa fa-times"></i></span></td>
                    <td><a href="#" type="button" class="btn btn-sm btn-sm-square btn-outline-primary m-2"><i class="fa fa-edit"></i></a> <a href="#" type="button" class="btn btn-sm btn-sm-square btn-outline-danger m-2"><i class="fa fa-trash-alt"></i></a></td>
                </tr>
                <tr>
                    <th scope="row">1</th>
                    <td>12.345.678</td>
                    <td>Juan Pérez</td>
                    <td>Recepcionista</td>
                    <td><span class="btn btn-sm btn-sm-square btn-outline-success m-2"><i class="fa fa-check"></i></span></td>
                    <td><a href="#" type="button" class="btn btn-sm btn-sm-square btn-outline-primary m-2"><i class="fa fa-edit"></i></a> <a href="#" type="button" class="btn btn-sm btn-sm-square btn-outline-danger m-2"><i class="fa fa-trash-alt"></i></a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection