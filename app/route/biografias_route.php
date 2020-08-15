<?php

$app->group('/biografia/', function () {
    $this->get('todo', function ($req, $res, $args) {
        $user = $req->getAttribute('user');
        $biogra = new Biografia(json_encode($user['user']->id + 0));
        $obtener=$biogra->getAll();
        $res->getBody()->write(json_encode($obtener));
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    });  

    $this->get('getbiografia/{numero}', function ($req, $res, $args) {
        $user = $req->getAttribute('user');
        $param = $req->getAttribute('route')->getArgument('numero');
        $biogra = new Biografia(json_encode($user['user']->id + 0));
        $obtener=$biogra->getMono($param);
        $payload = json_encode($obtener);
        $res->getBody()->write($payload);
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    }); 

    $this->put('new-biografia', function ($req, $res, $args) {
        $body = json_decode($req->getBody());
        $nombre=$body->nombre;
        $codigo=$body->codigo;
        /**extraer data */
        $user = $req->getAttribute('user');
        $biogra = new Biografia(json_encode($user['user']->id + 0));
        /**instanciar biografia */
        $agregar = $biogra->agregarBiografia($nombre,$codigo);
        /**metodo para agregar */
        $res->getBody()->write(json_encode($agregar));
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    });

    $this->put('actualiza-biografia/{id}', function ($req, $res, $args) {
        $body = json_decode($req->getBody());
        $nombre=$body->nombre;
        $codigo=$body->codigo;
        /**extraer data */
        $user = $req->getAttribute('user');//obtener usuario
        $param = $req->getAttribute('route')->getArgument('id');/**obtener id */
        $actualiza = new Biografia(json_encode($user['user']->id + 0));
        $respuesta = $actualiza->editarBio($nombre,$param,$codigo);
        $res->getBody()->write(json_encode($respuesta));
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    });

    $this->delete('eliminar-biografia/{id}', function ($req, $res, $args) {
        $user = $req->getAttribute('user');//obtener usuario
        $param = $req->getAttribute('route')->getArgument('id');//obtener id
        $elimina = new Biografia(json_encode($user['user']->id + 0));//instancia
        $respuesta = $elimina->deleteBio($param);
        $res->getBody()->write(json_encode($respuesta));
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    });
})->add($ensureAuth);

$app->group('/ventas/', function () {
    $this->get('index/{numero}', function ($req, $res, $args) {
        $user = $req->getAttribute('user');
        $param = $req->getAttribute('route')->getArgument('numero');
        $ventas = new Ventas(json_encode($user['user']->id + 0));

        $obtener=$ventas->getVenta($param);
        $res->getBody()->write(json_encode($obtener));
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    });
    $this->get('all', function ($req, $res, $args) {
        $user = $req->getAttribute('user');
        $ventas = new Ventas(json_encode($user['user']->id + 0));

        $obtener=$ventas->getAllVenta();
        $res->getBody()->write(json_encode($obtener));
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    });
    

    $this->put('newventa', function ($req, $res, $args) {
        $body = json_decode($req->getBody());
        $producto=$body->data;
        $fecha=$body->fecha;
        $folio=$body->folio;
        //extraer data
        $user = $req->getAttribute('user');
        $ventas = new Ventas(json_encode($user['user']->id + 0));
          
        $generar=$ventas->vender($producto,$folio,$fecha);/*
        $res->getBody()->write(json_encode($obtener));*/
        $res->getBody()->write(json_encode( $generar));
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    });

    $this->get('detalles/{folio}', function ($req, $res, $args) {
        $user = $req->getAttribute('user');
        $param = $req->getAttribute('route')->getArgument('folio');
        $ventas = new Ventas(json_encode($user['user']->id + 0));

        $obtener=$ventas->getDetalles($param);
        $res->getBody()->write(json_encode($obtener));
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    });
    
})->add($ensureAuth);