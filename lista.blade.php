@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            @if(Auth::User()->hasAnyRole(['admin']))
            <a href="{{ route('agregarcategoria') }}" 
                    class="btn btn-success">Nuevo Registro</a>
            @endif
        </div>
        <div class="col-md-9">
        </div>
    </div>        
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                   
                    <table class="table table-striped" data-toggle="dataTable" data-form="deleteForm">
                        <thead>
                          <tr>
                              <th>Nombre</th>
                              <th>Descripcion</th> 
                              <th>
                              </th>
                              <th>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                    @foreach ($categorias as $categoria)
                        <tr>
                            <td>
                                {{$categoria->nombre}}
                            </td>
                            <td>
                                {{$categoria->descripcion}}
                            </td>
                            <td>
                                <div class="col-6  text-center">
                                    <a class="btn btn-info " 
                                       href="{{ route('editarcategoria',$categoria->id) }}" 
                                       role="button">Editar</a>
                                </div>
                            </td>
                            <td> 
                                @if(Auth::User()->hasAnyRole(['admin']))
                                {!! Form::model($categoria, ['method' => 'delete', 'route' => ['eliminarcategoria', $categoria->id], 'class' =>'form-inline form-delete']) !!}
                                {!! Form::hidden('id', $categoria->id) !!}
                                {!! Form::submit(trans('Eliminar'), ['class' => 'btn btn-xs btn-danger delete', 'name' => 'delete_modal']) !!}
                                {!! Form::close() !!} 
                                @endif 
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $categorias->links() }}
                  
                </div>
            </div>
        </div>
    </div>
</div>

@include('modal.eliminar')

@endsection
 
@section('script')
<script src="{{ asset('js/eliminar.js')}}"></script>
@endsection
