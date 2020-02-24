@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Agregar categoría</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('nuevacategoria') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="nombre" 
                                    class="col-md-4 col-form-label text-md-right"
                                    >{{ __('Nombre') }}</label>

                            <div class="col-md-6">
                                <input id="nombre" type="text" 
                                    class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" 
                                    name="nombre" value="{{ old('nombre') }}" required autofocus>

                                @if ($errors->has('nombre'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('nombre') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group row">
                            <label for="descripcion" 
                                    class="col-md-4 col-form-label text-md-right"
                                    >{{ __('Descripcion') }}</label>

                            <div class="col-md-6">
                                <input id="descripcion" type="text" 
                                    class="form-control{{ $errors->has('descripcion') ? ' is-invalid' : '' }}" 
                                    name="descripcion" value="{{ old('descripcion') }}" 
                                    required autofocus>

                                @if ($errors->has('descripcion'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('descripcion') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group row mb-0">
                            <div class="col-6 text-center">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Guardar Datos') }}
                                </button>
                            </div>
                            <div class="col-6  text-center">
                                    <a class="btn btn-danger " 
                                       href="{{ route('listacategoria') }}" 
                                       role="button">Regresar</a>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
