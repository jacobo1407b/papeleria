<?php
class Producto{
    public function __construct($id){
        $this->id = $id;
    }
    public function obtenerPro($pagina){
        $db = new db();
        $db = $db->conecctionDB();
        $salto = ((9* $pagina)-9);
        $sql="select * from productos where user=? LIMIT 9 OFFSET $salto";
        $sql=$db->prepare($sql);
        $sql->bindValue(1,$this->id);
        $sql->execute();
        $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
        $pages = self::contar();
        return array("data" => $resultado,"paginas"=>$pages);
    }
    public function contar(){
        $db = new db();
        $db = $db->conecctionDB();
        $sql="select * from productos where user=? ";
        $sql=$db->prepare($sql);
        $sql->bindValue(1,$this->id);
        $sql->execute();
        $cou=$sql->rowCount();
        $paginas = $cou/9;
        return ceil($paginas); 
    }

    public function agregarProducto($nombre,$precio,$cantidad,$categoria,$url){
        $db = new db();
        $db = $db->conecctionDB();
        if($url){
            $sql="insert into productos
           values(null,?,?,?,?,?,?);";
           $sql=$db->prepare($sql);
           $sql->bindValue(1,$nombre);
           $sql->bindValue(2,$precio);
           $sql->bindValue(3,$cantidad);
           $sql->bindValue(4,$categoria);
           $sql->bindValue(5,$url);
           $sql->bindValue(6,$this->id);
        }else{
            $sql="insert into productos
           values(null,?,?,?,?,?,?);";
           $sql=$db->prepare($sql);
           $sql->bindValue(1,$nombre);
           $sql->bindValue(2,$precio);
           $sql->bindValue(3,$cantidad);
           $sql->bindValue(4,$categoria);
           $sql->bindValue(5,"");
           $sql->bindValue(6,$this->id);
        }
        if($sql->execute()){
            return array("message" => "Producto agregado con éxito","error"=>false);
        }
        else{
            return array("message" => "Error en el registro","error"=>true);
        }
    }
    public function editarProducto($idProducto,$nombre,$precio,$cantidad,$categoria,$url){
        $db = new db();
        $db = $db->conecctionDB();
        $sql="update productos set nombre=?,precio=?,cantidad=?,categoria=?,url=?where id=? and user=?";
        $sql=$db->prepare($sql);
        $sql->bindValue(1,$nombre);
        $sql->bindValue(2,$precio);
        $sql->bindValue(3,$cantidad);
        $sql->bindValue(4,$categoria);
        $sql->bindValue(5,$url);
        $sql->bindValue(6,$idProducto);
        $sql->bindValue(7,$this->id);
        if($sql->execute()){
            return array("message" => "Producto actualizado con éxito","error"=>false);
        }else{
            return array("message" => "Error al actualizar","error"=>true);
        }
    }
    public function eliminarProducto($idProduc){
        $db = new db();
        $db = $db->conecctionDB();
        $sql="delete from productos where id =? and user=?";
        $sql=$db->prepare($sql);
        $sql->bindValue(1,$idProduc);
        $sql->bindValue(2,$this->id);
        if($sql->execute()){
            return array("message" => "Producto eliminadocon éxito","error"=>false);
        }else{
            return array("message" => "Error al eliminar","error"=>true);
        }
    }

    public function todos(){
        $db = new db();
        $db = $db->conecctionDB();
        $sql="select * from productos where user=?";
        $sql=$db->prepare($sql);
        $sql->bindValue(1,$this->id);
        $sql->execute();
        $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }
}