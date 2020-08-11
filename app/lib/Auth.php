<?php
use Firebase\JWT\JWT;

class Auth{
    private static $secret_key = 'Sdw1s9x8@';
    private static $encrypt = ['HS256'];
    private static $aud = null;

    /* Encriptacion claves*/
    private static $clave  = 'enode://0x1C94B97BCC7e7C60C9e3DD5c2df01f28250ce497';
    private static $method = 'aes-256-cbc';



    private function iv(){
        return base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
    }
    public  function SignIn($data){
        $time = time();

        $token = array(
            'exp' => $time + (86400),
            'data' => $data
        );
        
        return self::encriptar(JWT::encode($token, self::$secret_key)) ;
    }

    public  function Check($token){
        if(empty($token)){
           return array(
               "msg"=>"json web token requerido",
               "error"=>true,
               "user"=>null
           );
        }else{

            $val= self::desEncriptar($token);
            try {
                $decode = JWT::decode(
                    $val,
                    self::$secret_key,
                    self::$encrypt
                );
                return self::GetData($val);
            } catch (Exception $th) {
                return array(
                    "error"=>true,
                    "user"=>null,
                    "msg"=>"json webtoken no valido"
                );
            }
        }
    
        /*if($decode->aud !== self::Aud()){
            throw new Exception("Invalid user logged in.");
        }*/
    }

    public  function GetData($token){
        $data = JWT::decode(
            $token,
            self::$secret_key,
            self::$encrypt
        )->data;
         return array(
             "error"=>false,
             "user"=>$data,
             "msg"=>"ok"
         );
    }

    private function Aud(){
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
            
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
            
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
            
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();
        echo json_encode($aud);
        return sha1($aud);
    }

     //metodos para encriptar
    private function encriptar($token){
        return openssl_encrypt ($token, self::$method, self::$clave, false, self::iv());
    }
    private function desEncriptar($token){
        $encrypted_data = base64_decode($token);
        return openssl_decrypt($token, self::$method, self::$clave, false, self::iv());
    }
}