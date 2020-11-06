<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SincronazedProducts extends Model
{

    public $arrUnidades = ['UN' => 1, 'FRC' => 1, 'CX' => 10, 'AMP' => 1, 'TB' => 1, 'UND' => 1];

    protected $fillable = ['sincronizar', 'sincronizado'];

    function getArrayFiledsMeEntregaNovo()
    {
        return [
            'acao' => 'novo',
            'barcode' => $this->cod_barra,
            'name' => $this->descricao,
            'price' => $this->vlr_venda,
            'in_stock' => $this->estoque,
            'update_if_exists' => true,
            'id_estoque_medida' => $this->arrUnidades[$this->undvenda]];
    }

    function getArrayFiledsMeEntregaEditar()
    {
//    $data = http_build_query($arrFields);

        return [
            'acao' => 'editar',
            'barcode' => $this->cod_barra,
            'name' => $this->descricao,
            'price' => $this->vlr_venda,
            'in_stock' => $this->estoque];
    }
    /*
         De-Para:
        // MeEntrega
            1. Unidade Un
            2. Pacote Pct
            3. Peça Pç
            4. Grama g
            5. Kilograma kg
            6. Litro L
            7. Metro m
            8. Metro Quadrado m²
            9. Metro Cúbico m³
            10. Caixa Cx

        // SPA
         UN
         FRC
         CX
         AMP
         TB
         UND
    */
}
