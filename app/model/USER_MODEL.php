<?php
 
class Usuario  {  
    public function getUser(){
        $db = new db();
        $db = $db->conecctionDB();
        $sql="select * from user";
        $sql=$db->prepare($sql);
        $sql->execute();
        $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }
    public function regist($firstName,$lastName,$lastN,$email,$password,$username,$direccion,$telefono){
        $db = new db();
        $db = $db->conecctionDB();
        if(self::buscarRegistro($email)>0){
            return array("message" => "Correo electronico en uso","error"=>true);
        }else{
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $sql="insert into user
           values(null,?,?,?,?,?,?,?,?);";
           $sql=$db->prepare($sql);
           $sql->bindValue(1,$firstName);
           $sql->bindValue(2,$lastName);
           $sql->bindValue(3,$lastN);
           $sql->bindValue(4,$direccion);
           $sql->bindValue(5,$telefono);
           $sql->bindValue(6,$email);
           $sql->bindValue(7,$username);
           $sql->bindValue(8,$password_hash);
        
           if($sql->execute()){
               return array("message" => "Usuario registrado","error"=>false);
           }
           else{
               return array("message" => "Error en el registro","error"=>true);
           }
        }
    }
    public function login($email,$password){
        $db = new db();
        $db = $db->conecctionDB();
        $query = "SELECT * FROM user WHERE email = ?";
        $query=$db->prepare($query);
        $query->bindValue(1,$email);
        $query->execute();
        $num = $query->rowCount();
        if($num > 0){
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $id = $row['id'];
            $nombre= $row['nombre'];
            $apellido1 = $row['apellidoPa'];
            $apellido2 = $row['apellidoMa'];
            $password2 = $row['password'];
            if(password_verify($password, $password2)){
                $token = array(
                        "id" => $id,
                        "nombre" => $nombre,
                        "apellido1" => $apellido1,
                        "apellido2"=> $apellido2,
                        "email" => $email
                );
                return $token;
            }else{
                return null;
            }
        }
    }

    private function buscarRegistro($email){
        $db = new db();
        $db = $db->conecctionDB();
        $sql="select * from user where email=?";
        $sql=$db->prepare($sql);
        $sql->bindValue(1,$email);
        $sql->execute();
        $count=$sql->rowCount();
        return $count;
    }
}