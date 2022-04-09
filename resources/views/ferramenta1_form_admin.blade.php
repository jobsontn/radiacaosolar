@extends('adminlte::page')

@section('title', 'Cálculo de Radiação')

@section('content_header')
    <h1>Cálculo de Radiação</h1>
@stop

@section('content')
    <p>Esta ferramenta permite calcular a irradiação solar diária média mensal em kWh/m² para uma superfície com qualquer valor de inclinação e direção. Utiliza dados do 2ª Edição do Atlas Brasileiro de Energia Solar, desenvolvido pelo INPE em 2017. Esta ferramenta foi desenvolvida em 2020, a partir do plano de atividade do bolsista e aluno do Curso de Bacharelado em Engenharia Elétrica do IFPE Campus Pesqueira Klinsmann Baltazar Ramos da Silva, orientado pelo Professor Dr. Manoel Henrique de Oliveira Pedrosa Filho.</p>
    <div class="row justify-content-md-center">
        <div class="col col-lg-3 col-12 ">
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
        <!-- <div class="col col-lg-8 col-12 offset-md-1"> -->
        <div class="col col-lg-8 col-12" style="padding-left: 30px;">
            @if(isset($mediadiariahorizontal))
                <p><strong>Latitude:</strong> {{ number_format($latitude, 1, '.', '') }}</p>
                <p><strong>Longitude:</strong> {{ number_format($longitude, 1, '.', '') }}</p>
                <p><strong>Inclinação:</strong> {{ number_format($inclinacao, 1, '.', '') }}</p>
                <p><strong>Orientação:</strong> {{ number_format($orientacao, 1, '.', '') }}</p>
                <p><strong>Tempo de processamento:</strong> {{ $diff }}</p>
                <p><strong>Média diária horizontal:</strong> {{ number_format($mediadiariahorizontal, 1, '.', '') }}</p>
                <p><strong>Média diária inclinada:</strong> {{ number_format($mediadiaria, 1, '.', '') }}</p>
                <div class="table-responsive d-sm-none d-md-block">
                    <table class="table table-sm table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">jan</th>
                        <th scope="col">fev</th>
                        <th scope="col">mar</th>
                        <th scope="col">abr</th>
                        <th scope="col">mai</th>
                        <th scope="col">jun</th>
                        <th scope="col">jul</th>
                        <th scope="col">ago</th>
                        <th scope="col">set</th>
                        <th scope="col">out</th>
                        <th scope="col">nov</th>
                        <th scope="col">dez</th>
                        <th scope="col">Média</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Plano Horizontal</th>
                            @foreach ($Hm as $i)
                            <td>{{ number_format($i, 1, '.', '') }}</td>
                            @endforeach
                            <td>{{ number_format($mediadiariahorizontal, 1, '.', '') }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Plano Inclinado</th>
                            @foreach ($diario as $i)
                            <td>{{ number_format($i, 1, '.', '') }}</td>
                            @endforeach
                            <td>{{ number_format($mediadiaria,1, '.', '') }}</td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                <div class="table-responsive d-sm-block d-md-none">
                    <table class="table table-sm table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">Mês</th>
                        <th scope="col">Plano Horizontal</th>
                        <th scope="col">Plano Inclinado</th>
                        </tr>
                    </thead>
                    <tbody>

                        @for ($i = 0; $i < 12; $i++)
                        <tr>
                            <th scope="row">{{ $i + 1 }}</th>
                            <td>{{ number_format($Hm[$i], 1, '.', '') }}</td>
                            <td>{{ number_format($diario[$i], 1, '.', '') }}</td>
                        </tr>
                        @endfor
                        <tr>
                            <th scope="row">Média</th>
                            <td>{{ number_format($mediadiariahorizontal, 1, '.', '') }}</td>
                            <td>{{ number_format($mediadiaria, 1, '.', '') }}</td>
                        </tr>

                    </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')

@stop
