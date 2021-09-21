<?php


class ajaxto
{

    private
        $httpCode_     = null,
        $clientProcess = [],
        $validation    = [],
        $data          = null;

    private static
        $instance = null;


    // CREATE INSTANCE OR GET INSTANCED >>
    public static function new(){
        self::$instance = new self();
        return self::$instance;
    }

    //SINGLETON
    public static function use(){
        if(self::$instance){ return self::$instance; }
        return self::$instance = new self();
    }
    // CREATE INSTANCE OR GET INSTANCED //


    public function httpCode($code){
        $this->httpCode_ = $code;
        return $this;
    }


    public function data($data){
        $this->data = $data;
        return $this;
    }


    // CLIENT PROCESS >>
    public function direct($url, $timeout = 0, $target = '_self'){
        $this->clientProcess['direct'] = compact(['url', 'timeout', 'target']);
        return $this;
    }


    public function reload($timeout = null){
        $this->clientProcess['reload'] = compact('timeout');
        return $this;
    }


    public function innerHtml($selector, $html){
        $this->clientProcess['innerHtml'][] = compact('selector', 'html');
        return $this;
    }


    public function addClass($selector, $class){
        $this->clientProcess['class'][] = [
            'selector' => $selector,
            'class' => $class,
            'process' => 'add'
        ];
        return $this;
    }


    public function removeClass($selector, $class){
        $this->clientProcess['class'][] = [
            'selector' => $selector,
            'class' => $class,
            'process' => 'remove'
        ];
        return $this;
    }


    public function addRemoveClass($selector, $addClass, $removeClass = null){
        if($addClass)   { $this->addClass($selector, $addClass); }
        if($removeClass){ $this->removeClass($selector, $removeClass); }
        return $this;
    }
    // CLIENT PROCESS //


    // VALIDATION >>
    public function setValidationFields($fields){
        $this->validation = array_merge($this->validation, $fields);
    }


    public function valid(string $field, string $msg = null, $code = null){
        $this->validation[$field] = [
            'field' => $field,
            'msg' => $msg,
            'status' => true,
            'code' => $code
        ];
        return $this;
    }


    public function invalid(string $field, string $msg = null, $code = null){
        $this->validation[$field] = [
            'field' => $field,
            'msg' => $msg,
            'status' => false,
            'code' => $code
        ];
        return $this;
    }


    public function validationClear($field){
        $this->validation[$field] = [
            'field' => $field,
            'msg' => null,
            'status' => null,
            'code' => null
        ];
        return $this;
    }


    public function unsetValid(string $field){
        unset($this->validation[$field]);
        return $this;
    }


    public function isInvalid():bool{
        if(!$this->validation){ return false; }
        foreach($this->validation as $field){
            if(!$field['status']){ return true; }
        }
        return false;
    }
    // VALIDATION //



    // RESPONSE >>
    protected function res(bool $status, string $msg = null, string $code = null, $data = null) : bool {

        if($this->httpCode_){ http_response_code($this->httpCode_); }
        if(!$data){ $data = $this->data; }

        $resArr['status'] = $status;

        if($msg){
            $cleanMsg = $this->cleaningAttrFromMsg($msg);
            $resArr['msg'] = $cleanMsg['msg'];

            if($cleanMsg['behavior']){
                $resArr['msgBehavior'] = $cleanMsg['behavior'];
            }
        }
        if($code){ $resArr['code'] = $code; }
        if($data){ $resArr['data'] = $data; }
        if($this->validation){ $resArr['validation'] = $this->validation; }
        if($this->clientProcess){ $resArr['clientProcess'] = $this->clientProcess; }

        echo json_encode($resArr);
        return $status;
    }

    public function resTrue(string $msg = null, string $code = null, $data = null):bool{
        return $this->res(true, $msg, $code, $data);
    }

    public function resFalse(string $msg = null, string $code = null, $data = null):bool{
        return $this->res(false, $msg, $code, $data);
    }

    public function resTrueAlert(string $msg = null, string $code = null, $data = null):bool{
        return $this->res(true, '{alert}:' . $msg, $code, $data);
    }

    public function resFalseAlert(string $msg = null, string $code = null, $data = null):bool{
        return $this->res(false, '{alert}:' . $msg, $code, $data);
    }
    // RESPONSE //


    // PRIVATE >>
    private function cleaningAttrFromMsg($msg):array{

        $return = [
            'msg'     => $msg,
            'behavior' => null
        ];

        if(strpos($msg, '{alert}:') === 0){
            $return['msg'] = substr($msg, 8);
            $return['behavior'] = 'alert';
        }

        return $return;
    }
    // PRIVATE //

}