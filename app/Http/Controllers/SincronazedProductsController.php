<?php

namespace App\Http\Controllers;

use App\Logs;
use App\SincronazedProducts;
use Illuminate\Http\Request;

class SincronazedProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request) && $request->get('filter') != null) {

            $filter = $request->get('filter');

            $arrProduct = \DB::connection('mysql')->select(\DB::raw("select cod_barra, descricao, vlr_venda, estoque, controlado, antibio, undvenda
                                                                 from resultpdv.produtos
                                                                 where descricao like '%$filter%'
                                                                 order by descricao asc;"));
            $objSincronazedProducts = new SincronazedProducts();
            foreach ($arrProduct as $product) {
                if (!is_null($product->cod_barra)) {
                    $sincronaze = $objSincronazedProducts->firstWhere('cod_barra', $product->cod_barra);
                    $product->{'sincronizar'} = isset($sincronaze) && ($sincronaze->sincronizar == 'true') ? "checked='checked'" : false;
                }
            }
            return view('ava.deliveryenable', ['arrProduct' => $arrProduct]);
        } else {
            return view('ava.deliveryenable', ['arrProduct' => null]);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        file_put_contents('teste.txt', print_r($_REQUEST, true), FILE_APPEND);

        try {
            $sincronaze = (new SincronazedProducts())->firstWhere('cod_barra', $request->codBarras);
            file_put_contents('teste1.txt', print_r($sincronaze, true), FILE_APPEND);

            if (isset($sincronaze)) {
                $sincronaze->sincronizar = $request->sincronizar;
                $sincronaze->save();
                $this->updateRemote($sincronaze);
            } else {
                $arrProduct = \DB::connection('mysql')->select(\DB::raw("select codprod, cod_barra, descricao, vlr_venda, estoque, controlado, antibio, undvenda
                                                                 from resultpdv.produtos
                                                                 where cod_barra = '$request->codBarras';"));
                if (isset($arrProduct[0])) {
                    $objSincronazedProducts = new SincronazedProducts();
                    $objSincronazedProducts->codprod = $arrProduct[0]->codprod;
                    $objSincronazedProducts->cod_barra = $arrProduct[0]->cod_barra;
                    $objSincronazedProducts->descricao = $arrProduct[0]->descricao;
                    $objSincronazedProducts->undvenda = $arrProduct[0]->undvenda;
                    $objSincronazedProducts->estoque = $arrProduct[0]->estoque;
                    $objSincronazedProducts->vlr_venda = $arrProduct[0]->vlr_venda;
                    $objSincronazedProducts->controlado = $arrProduct[0]->controlado;
                    $objSincronazedProducts->antibio = $arrProduct[0]->antibio;
                    $objSincronazedProducts->sincronizar = $request->sincronizar;
                    $res = $objSincronazedProducts->save();
                    $this->updateRemote($objSincronazedProducts);
                }
            }
        } catch (\Exception $e) {
            file_put_contents('exception.txt', substr(print_r($e->getMessage(), true), 0, 1024), FILE_APPEND);

            $objLogs = new Logs();
            $objLogs->message(substr(print_r($e->getMessage(), true), 0, 1024));
            $objLogs->save();

        }

        return json_encode('ok');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\SincronazedProducts $sincronazeProducts
     * @return \Illuminate\Http\Response
     */
    public function show(SincronazedProducts $sincronazeProducts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\SincronazedProducts $sincronazeProducts
     * @return \Illuminate\Http\Response
     */
    public function edit(SincronazedProducts $sincronazeProducts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\SincronazedProducts $sincronazeProducts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SincronazedProducts $sincronazeProducts)
    {
//        $message->update($request->all());
        file_put_contents('teste.txt', 1);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\SincronazedProducts $sincronazeProducts
     * @return \Illuminate\Http\Response
     */
    public function destroy(SincronazedProducts $sincronazeProducts)
    {
        //
    }

    public function updateRemote(SincronazedProducts $objSincronazedProducts)
    {
        file_put_contents('sinc.txt' , print_r($objSincronazedProducts->getChanges()['sincronizar'], true),FILE_APPEND );


        $restClient = new \App\Http\Controllers\RestClient(env('URL_MEENTREGA'), '', env('KEY_MEENTREGA'), true);

        $isDebug = env('APP_DEBUG');

        if($isDebug){
            $arrFields = ['acao' => 'editar', 'barcode' => '7896033241083', 'name' => 'ASPIRADOR LILLO NASAL ROSA', 'price' => 18.99, 'in_stock' => 3];
            $data = http_build_query($arrFields);
//            $data = 'acao=editar&barcode=7896033241083&name=ASPIRADOR LILLO NASAL ROSA&price=18.99&in_stock=3';
        } else {

        }

        $restClient->recurso = 'estoque_item_preco';

        $objLogs = new Logs();
        $objLogs->message = "Post [$data]. \nPath [$restClient->recurso]. \nDebug [$isDebug]. \n";

        try {
            $resultPost = $restClient->post($data);
            $objLogs->message = "Post [$data]. \nPath [$restClient->recurso]. \nDebug [$isDebug]. \nResult [$resultPost]";
        } catch (\Exception $e) {
            $objLogs->message = "Post [$data]. \nPath [$restClient->recurso]. \nDebug [$isDebug]. \nException [$e->getMessage()]";
        } finally {
            $objLogs->save();
            if($isDebug){
                file_put_contents('debug.txt', print_r($objLogs, true) , FILE_APPEND);
            }
        }

        return json_encode($resultPost);
    }

    public static function testRemote()
    {
        $restClient = new \App\Http\Controllers\RestClient(env('URL_MEENTREGA'), '', env('KEY_MEENTREGA'), true);

        $restClient->recurso = 'testkey';
        $resultLogin = $restClient->listar();

        $retorno = '<html><body>';
        $retorno .= 'Response Headers: <br><pre>' . print_r($restClient->responseHeaders, true) . '</pre><br>';
        $retorno .= 'Recurso: testkey <br>';
        $retorno .= '<pre>' . print_r($resultLogin, true) . '</pre><br>';
        $retorno .= $retorno . '</body></html>';

        return $retorno;
    }

    public static function sincronizeRemote()
    {
        file_put_contents('teste.txt', 1, true, FILE_APPEND);
    }

}
