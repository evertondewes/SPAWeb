@extends('layouts.app')

@section('content')

    <script>
        function onChangeProduct(item) {

            var token = $('#update_token').val();
            $.ajax({
                url: "{{ route('sincronazedproducts.store') }}",
                type: "POST",
                dataType: "json",
                ContentType: 'application/json',
                headers: {'X-CSRF-TOKEN': token},
                data: {
                    'codBarras': item.value,
                    'sincronizar': item.checked,
                    '_token': token
                },
                success: function (dados) {

                    // alert(dados);
                },
                error:function(){
                   // alert("error!!!!");
                }
            });
        }
    </script>
    <div class="container">
        <h1 class="page-header">Produtos</h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Produtos</a></li>
            <li class="breadcrumb-item active">Enviar</li>
        </ol>


        <form method="POST" action="{{ route('ava.deliveryenable') }}">
            <div class="table-responsive">
                {{ csrf_field() }}
                <input id="update_token" name="update_token" type="hidden" value="{{csrf_token()}}">
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
                            <th>Sinc</th>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th>Valor Venda (app)</th>
                            <th>Estoque</th>
                            <th>Controlado</th>
                            <th>Antibiótico</th>
                            <th>Tipo de Unidades</th>
                            <th>Utlima alteração</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($arrProduct as $product)
                            <tr>
                                <td><input type="checkbox" name="adopted" id="adopted" value="{{ $product->cod_barra }}" {{ $product->{'sincronizar'}  }}
                                           onclick="onChangeProduct(this);"></td>
                                <th>{{ $product->cod_barra }}</th>
                                <td style="text-align: left">{{ $product->descricao }}</td>
                                <td style="text-align: left">{{ number_format($product->vlr_venda, 2, ',', '') }}</td>
                                <td style="text-align: left">{{ number_format($product->estoque, 2, ',', '') }}</td>
                                <td style="text-align: left">{{ $product->controlado }}</td>
                                <td style="text-align: left">{{ $product->antibio }}</td>
                                <td style="text-align: left">{{ $product->undvenda }}</td>
                                <td style="text-align: left"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </form>
    </div>
@endsection
