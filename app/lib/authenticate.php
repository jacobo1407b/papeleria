<?php
use Zend\Config\Config;
use Zend\Config\Factory;
use Zend\Http\PhpEnvironment\Request;



$ensureAuth = function ($req, $res, $next) {
    $cabeza = new Request();
    $jsonweb = new Auth();
    $authHeader = $cabeza->getHeader('authorization');
    if(!$authHeader){
        $pay = array(
            "msg"=>"token no encontrado",
            "error"=>true,
            "user"=>null
        );
        $res->getBody()->write(json_encode($pay));
        return $res;
    }
    list($jwt) = sscanf( $authHeader->toString(), 'Authorization: BEARER %s');
    $respuesta = $jsonweb->Check($jwt);
    //echo json_encode($respuesta);
    if(!$respuesta['error'] ){
        $req = $req->withAttribute('user', $respuesta);
        $res = $next($req, $res);
        return $res;
    }else{
        $res->getBody()->write(json_encode($respuesta));
        return $res;
    }
};
