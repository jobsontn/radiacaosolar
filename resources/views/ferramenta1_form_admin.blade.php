@extends('adminlte::page')

@section('title', 'Cálculo de Radiação')

@section('content_header')
    <h1>Cálculo de Radiação</h1>
@stop

@section('content')
    <p>Esta ferramenta permite calcular a irradiação solar diária média mensal em kWh/m² para uma superfície com qualquer valor de inclinação e direção. Utiliza dados do 2ª Edição do Atlas Brasileiro de Energia Solar, desenvolvido pelo INPE em 2017. Esta ferramenta foi desenvolvida em 2020, a partir do plano de atividade do bolsista e aluno do Curso de Bacharelado em Engenharia Elétrica do IFPE Campus Pesqueira Klinsmann Baltazar Ramos da Silva, orientado pelo Professor Dr. Manoel Henrique de Oliveira Pedrosa Filho.</p>
    <div class="row justify-content-md-center">
        <div class="col col-lg-5 col-12 ">
            <form action="{{ route('ferramenta1_action') }}" method='GET'>
                <div class="form-group has-validation">
                    <label for="latitude">Latitude</label>
                    <input type="number" step="0.0000000001" class="form-control" id="latitude" name="latitude" aria-describedby="latitudeHelp" value="{{old('latitude')}}" required>
                    <small id="latitudeHelp" class="form-text text-muted">Deve ser informado um valor numérico entre -90 e 90</small>
                    @error('latitude')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $message }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="longitude">Longitude</label>
                    <input type="number" step="0.0000000001" class="form-control" id="longitude" name="longitude" aria-describedby="longitudeHelp" value="{{old('longitude')}}" required>
                    <small id="longitudeHelp" class="form-text text-muted">Deve ser informado um valor numérico entre -180 e 180</small>
                    @error('longitude')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $message }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="inclinacao">Inclinação</label>
                    <input type="number" class="form-control" id="inclinacao" name="inclinacao" aria-describedby="inclinacaoHelp" value="{{old('inclinacao')}}" required>
                    <small id="inclinacaoHelp" class="form-text text-muted">Deve ser informado um valor numérico entre 0 e 90</small>
                    @error('inclinacao')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $message }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="orientacao">Orientação</label>
                    <input type="number" class="form-control" id="orientacao" name="orientacao" aria-describedby="orientacaoHelp" value="{{old('orientacao')}}" required>
                    <small id="orientacaoHelp" class="form-text text-muted">Deve ser informado um valor numérico entre 0 e 360</small>
                    @error('orientacao')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $message }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Calcular</button>
            </form>
        </div>
        <div class="col col-lg-5 col-12 offset-md-1">
            @if(isset($mediadiariahorizontal))
                <p><strong>Latitude:</strong> {{ $latitude }}</p>
                <p><strong>Longitude:</strong> {{ $longitude }}</p>
                <p><strong>Inclinação:</strong> {{ $inclinacao }}</p>
                <p><strong>Orientação:</strong> {{ $orientacao }}</p>
                <p><strong>Duração:</strong> {{ $diff }}</p>
                <p><strong>Média diária horizontal:</strong> {{ $mediadiariahorizontal }}</p>
                <p><strong>Média diária inclinada:</strong> {{ $mediadiaria }}</p>
                
            @endif
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')

@stop
