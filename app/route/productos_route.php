<?php

//require_once("../model/USER_MODEL.php");


$app->group('/productos/', function () {
    $this->get('index', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello Users');
    });  

    $this->get('getproducto/{numero}', function ($req, $res, $args) {
        $user = $req->getAttribute('user');
        $param = $req->getAttribute('route')->getArgument('numero');
        $producto = new Producto(json_encode($user['user']->id + 0));
        $obtener = $producto->obtenerPro($param);
        $payload = json_encode($obtener);
        $res->getBody()->write($payload);
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    });
    $this->get('todos', function ($req, $res, $args) {
        $user = $req->getAttribute('user');
        $producto = new Producto(json_encode($user['user']->id + 0));
        $obtener = $producto->todos();
        $payload = json_encode($obtener);
        $res->getBody()->write($payload);
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    });

    $this->post('new-product', function ($req, $res, $args) {
        $body = json_decode($req->getBody());
        $nombre=$body->nombre;
        $precio=$body->precio;
        $cantidad=$body->cantidad;
        $categoria=$body->categoria;
        $url=$body->url;
        /*obtener datos del body */
        $user = $req->getAttribute('user');
        $producto = new Producto(json_encode($user['user']->id + 0));
        /*instanciar modelo e inicializar */
        $agregar = $producto->agregarProducto($nombre,$precio,$cantidad,$categoria,$url);
        $payload = json_encode($agregar);
        return $res->getBody()->write($payload);
    });

    $this->put('update/producto/{id}', function ($req, $res, $args) {
        $body = json_decode($req->getBody());
        $nombre=$body->nombre;
        $precio=$body->precio;
        $cantidad=$body->cantidad;
        $categoria=$body->categoria;
        $url=$body->url;
        /*obtener datos del body */
        $param = $req->getAttribute('route')->getArgument('id');
        /*obtener id de producto */
        $user = $req->getAttribute('user');
        $producto = new Producto(json_encode($user['user']->id + 0));
        /*instanciar modelo e inicializar */
        $actualiza = $producto->editarProducto($param,$nombre,$precio,$cantidad,$categoria,$url);
        $payload = json_encode($actualiza);
        return $res->getBody()->write($payload);
    });

    $this->delete('delete/producto/{id}', function ($req, $res, $args) {
        $param = $req->getAttribute('route')->getArgument('id');
        $user = $req->getAttribute('user');
        $producto = new Producto(json_encode($user['user']->id + 0));
        $elimi = $producto->eliminarProducto($param);
        $payload = json_encode($elimi);
        return $res->getBody()->write($payload);
    }); 
})->add($ensureAuth);


// return $res->getBody()->write(json_encode($user['user']->id));