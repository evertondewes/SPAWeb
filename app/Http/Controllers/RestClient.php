<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestClient extends Controller
{

    public $data = array();
    public $uri = null;
    public $recurso = null;
    public $responseHeaders = array();
    public $requestHeaders = array();
    public $ch = null;

    public function __construct($uri_base, $recurso, $strChave, $bolIgnorarValidacaoCertificado = false, $ContentType = 'Content-Type: application/x-www-form-urlencoded') {

        $this->requestHeaders[] = $ContentType;

        $this->requestHeaders[] = 'authorization: ' . $strChave;
        $this->uri = $uri_base;
        $this->recurso = $recurso;

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->requestHeaders);
        curl_setopt($this->ch, CURLOPT_HEADERFUNCTION, array($this, '_header_callback'));
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        if($bolIgnorarValidacaoCertificado) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        }

    }

    public function consultar($id) {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->ch, CURLOPT_URL, $this->uri . $this->recurso . '/' . trim($id));
        return curl_exec($this->ch);
    }

    public function listar() {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->ch, CURLOPT_URL, $this->uri . $this->recurso);
        return json_decode(curl_exec($this->ch));
    }

    public function buscar($query, $prefixo = '') {
        $strUrl = $this->uri . $this->recurso . '?'. $prefixo .
            urlencode(urldecode(http_build_query($query)));
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->ch, CURLOPT_URL, $strUrl);
        $retorno = curl_exec($this->ch);
        if ($retorno === false) {
            throw new Exception(curl_error($this->ch), curl_errno($this->ch));
        }
        return $retorno;
    }

    public function cadastrar($data) {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->ch, CURLOPT_URL, $this->uri  . $this->recurso . '/');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($this->ch);

        if (isset($this->responseHeaders['Location'])) {
            return substr($this->responseHeaders['Location'], strlen($this->uri . $this->recurso) + 1);
        } else {
            throw new Exception('Erro ao cadastrar ' . $this->recurso . '. Resposta: ' . print_r($result, true) . ' Cabeçalho Retorno: ' . print_r($this->responseHeaders, true). ' Cabeçalho Enviado: ' . print_r($this->requestHeaders, true). ' Dados Enviados: ' . print_r($data, true).' URL: '.$this->uri );
        }
    }


    public function cadastrarOctetStreamFile(array $filesPath, $fieldName = 'file', $connect_timeout = 360, $request_timeout = 1200) {

        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT ,$connect_timeout); // ESPERA ATÉ 6 MINUTOS PARA CONECTAR
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $request_timeout); // ESPERA ATÉ 20 MINUTOS PELA RESPOSTA DA REQUISIÇÃO

        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->ch, CURLOPT_URL, $this->uri . $this->recurso . '/' );

        $fields = [];
        foreach ($filesPath as $filePath) {
            $fields[$fieldName] =  new \CurlFile($filePath, 'application/octet-stream', 'file');
        }

        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $fields);

        return curl_exec($this->ch);
    }



    public function alterar($id, $data) {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($this->ch, CURLOPT_URL, $this->uri . $this->recurso. '/' . trim($id));
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($data));

        $result = curl_exec($this->ch);
        if ($result) {
            throw new Exception('Erro ao alterar ' . $this->recurso . '.  Resposta: ' . print_r($result, true) . ' Cabeçalho: ' . print_r($this->responseHeaders, true));
        }
    }

    public function excluir($id) {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($this->ch, CURLOPT_URL, $this->uri . $this->recurso . '/' . trim($id));
        return curl_exec($this->ch);
    }

    public function _header_callback($curl, $header_line) {
//        list ($key, $value) = explode(': ', $header_line);
//        $this->responseHeaders[$key] = $value;
        $this->responseHeaders[] = print_r($header_line, true);

        return strlen($header_line);
    }

    public function post($data) {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->ch, CURLOPT_URL, $this->uri  . $this->recurso ); // . '?acao=editar'  );
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, ($data));
        return json_decode(curl_exec($this->ch));
    }
}
