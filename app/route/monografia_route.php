<?php

$app->group('/monografia/', function () {
    $this->get('index', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello Users');
    });  

    $this->get('getmonografia/{numero}', function ($req, $res, $args) {
        $user = $req->getAttribute('user');
        $param = $req->getAttribute('route')->getArgument('numero');
        $biogra = new Monografia(json_encode($user['user']->id + 0));
        $obtener=$biogra->getMono($param);
        $payload = json_encode($obtener);
        $res->getBody()->write($payload);
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    }); 

    $this->post('new-monografia', function ($req, $res, $args) {
        $body = json_decode($req->getBody());
        $nombre=$body->nombre;
        /**extraer data */
        $user = $req->getAttribute('user');
        $setMono = new Monografia(json_encode($user['user']->id + 0));
        /**instanciar biografia */
        $agregar = $setMono->agregarMonografia($nombre);
        /**metodo para agregar */
        $res->getBody()->write(json_encode($agregar));
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    });

    $this->put('actualiza-monografia/{id}', function ($req, $res, $args) {
        $body = json_decode($req->getBody());
        $nombre=$body->nombre;
        /**extraer data */
        $user = $req->getAttribute('user');//obtener usuario
        $param = $req->getAttribute('route')->getArgument('id');/**obtener id */
        $actualiza = new Monografia(json_encode($user['user']->id + 0));
        $respuesta = $actualiza->editarMonografia($nombre,$param);
        $res->getBody()->write(json_encode($respuesta));
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    });

    $this->delete('delete-monografia/{id}', function ($req, $res, $args) {
        $user = $req->getAttribute('user');//obtener usuario
        $param = $req->getAttribute('route')->getArgument('id');//obtener id
        $elimina = new Monografia(json_encode($user['user']->id + 0));//instancia
        $respuesta = $elimina->deleteMonografia($param);
        $res->getBody()->write(json_encode($respuesta));
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    });
})->add($ensureAuth);