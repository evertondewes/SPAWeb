@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="page-header">Mensagens</h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Mensagens</a></li>
            <li class="breadcrumb-item active">Enviar</li>
        </ol>


        <form method="POST" action="{{ route('ava.deliveryenable') }}">
            <div class="table-responsive">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="text">Produtos</label>
                    <input type="text" name="filter"
                           class="form-control  {{ $errors->has('text') ? 'is-invalid' : '' }}" placeholder="Filtro"
                           required>
                    @if($errors->has('text'))
                        <span class="help-block">
                        <strong>{{ $errors->first('text') }}</strong>
                    </span>
                    @endif
                    <button class="btn btn-info">Enviar</button>
                </div>
            </div>
            @if(isset($arrProduct) && !is_null($arrProduct))
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th> asdsa</th>
                            <th>codigo</th>
                            <th>descricao</th>
                            <th>vlr_venda</th>
                            <th>estoque</th>
                            <th>controlado</th>
                            <th>antibio</th>
                            <th>undvenda</th>
                            <th>updated_at</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($arrProduct as $product)
                            <tr>
                                <td><input type="checkbox" name="adopted" id="adopted" value="1"></td>
                                <th>{{ $product->cod_barra }}</th>
                                <td style="text-align: left">{{ $product->descricao }}</td>
                                <td style="text-align: left">{{ $product->vlr_venda }}</td>
                                <td style="text-align: left">{{ $product->estoque }}</td>
                                <td style="text-align: left">{{ $product->controlado }}</td>
                                <td style="text-align: left">{{ $product->antibio }}</td>
                                <td style="text-align: left">{{ $product->undvenda }}</td>
                                <td style="text-align: left"> </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </form>

    </div>
@endsection
