<?php
use App\Model\UsuarioModel;

$app->group('/usuario/', function () {
    
    $this->get('test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello Users');
    });
    
    $this->get('getAll', function ($req, $res, $args) {
        $um = new UsuarioModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->ObtenerTodos()
            )
        );
    });

    $this->get('getSend', function ($req, $res, $args) {
        $um = new UsuarioModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->ObtenerPorEnviar()
            )
        );
    });

    $this->get('getEmail/{id}', function ($req, $res, $args) {
        $um = new UsuarioModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->ObtenerEmail($args['id'])
            )
        );
    });      
    
    $this->post('updateEmail', function ($req, $res) {
        $um = new UsuarioModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->ActualizarEmail(
                    $req->getParsedBody()
                )
            )
        );
    });
    
    $this->post('delete/{id}', function ($req, $res, $args) {
        $um = new UsuarioModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Delete($args['id'])
            )
        );
    });    

    $this->post('sendEmail', function ($req, $res) {
        $um = new UsuarioModel();
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->EnviarEmailUsuario(
                    $req->getParsedBody()
                )
            )
        );
    });
});