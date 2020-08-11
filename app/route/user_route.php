<?php

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});
$app->group('/user/', function () {
    
    $this->put('register', function ($req, $res, $args) {
        $emp = json_decode($req->getBody());
        $nombre=$emp->nombre;
        $apaterno=$emp->apellidop;
        $amaterno=$emp->apellidom;
        $email=$emp->email;
        $password=$emp->password;
        $username=$emp->username;
        $direccion=$emp->direccion;
        $telefono=$emp->telefono;

        $user = new Usuario();
        $resultado = $user->regist($nombre,$apaterno,$amaterno,$email,$password,$username,$direccion,$telefono);
        $payload = json_encode($resultado);
        $res->getBody()->write($payload);
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    });

    $this->put('login/auth', function ($req, $res, $args) {
        $emp = json_decode($req->getBody());
        $email=$emp->email;
        $password=$emp->password;
        $user = new Usuario();
        $respuesta = $user->login($email,$password);
        if($respuesta){
            $aut = new Auth();
            $jwt = $aut->SignIn($respuesta);
            $payload = json_encode(
                array(
                    "message" => "Successful login.",
                    "jwt" => $jwt,
                    "email" => $email,
                    "error"=>false
                ));
            $res->getBody()->write($payload);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
        }else{
            $payload = json_encode(
                array(
                    "text"=>"Error",
                    "message" => "Correo o contraseña incorrectos",
                    "error"=>true
                ));
            $res->getBody()->write($payload);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    });
    
}); 

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});


