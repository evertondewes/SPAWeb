<?php

namespace App\Http\Controllers;

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
                                                                 where descricao like '%$filter%';"));

            $strFilter = '';

            $objSincronazedProducts = new SincronazedProducts();

            foreach ($arrProduct as $product) {
                if(!is_null($product->cod_barra)) {
                    $sincronazed = $objSincronazedProducts->firstWhere('cod_barra', $product->cod_barra);

//                    $strFilter .= $product->cod_barra . ',';
                }
//                $product->{'updated_at'} = 'teste';
            }

//            dd($objSincronazedProducts);


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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\SincronazedProducts $sincronazedProducts
     * @return \Illuminate\Http\Response
     */
    public function show(SincronazedProducts $sincronazedProducts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\SincronazedProducts $sincronazedProducts
     * @return \Illuminate\Http\Response
     */
    public function edit(SincronazedProducts $sincronazedProducts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\SincronazedProducts $sincronazedProducts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SincronazedProducts $sincronazedProducts)
    {
//        $message->update($request->all());
        file_put_contents('teste.txt', 1);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\SincronazedProducts $sincronazedProducts
     * @return \Illuminate\Http\Response
     */
    public function destroy(SincronazedProducts $sincronazedProducts)
    {
        //
    }
}
