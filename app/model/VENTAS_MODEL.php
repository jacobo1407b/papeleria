<?php

class Ventas {
    public function __construct($id){
        $this->id = $id;
        $this->conectar = new db();
    }

    public function getVenta($pagina){
        $conexion = $this->conectar->conecctionDB();
        $salto = ((20* $pagina)-20);
        $sql="select * from ventas where user=? LIMIT 20 OFFSET $salto";
        $sql=$conexion->prepare($sql);
        $sql->bindValue(1,$this->id);
        $sql->execute();
        $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
        $pages = self::contar();
        return array("data"=>$resultado,"paginas"=>$pages);
    }

    private function contar(){
        $conexion = $this->conectar->conecctionDB();
        $sql="select * from ventas where user=?";
        $sql=$conexion ->prepare($sql);
        $sql->bindValue(1,$this->id);
        $sql->execute();
        $cou=$sql->rowCount();
        $paginas = $cou/20;
        return ceil($paginas); 
    }


    public function vender($data, $folio,$fecha){
        $newDate = $fecha;
        $conexion = $this->conectar->conecctionDB();
        $errores = array();
        //comprobar stock
        foreach($data as  $clave=>$value){
            $cantidad = self::countProduct($value->id);
            foreach($cantidad as $clave=>$val){
                if($val['cantidad']>$value->cantidad){

                }else{
                    $produno=$val['nombre'];
                    $newErr= array("error"=>true,"message"=>"No hay suficiente $produno");
                    array_push($errores,$newErr);
                }

            }
            
        }
        //conprobar errores
        if (empty($errores)) {
            $grantotal = 0;
            foreach($data as  $clave=>$value){
                $nuevoValor=0;
                $re = self::countProduct($value->id);//buscar la acantidad actual
                $paraquitar = $value->cantidad + 0;
                foreach($re as $cl=>$val){
                    $convertir = $val['cantidad']+0;
                    $nuevoValor = $convertir-$paraquitar;
                }
                $idproducto = $value->id + 0;
                self::restarStock($nuevoValor,$idproducto);
                $producto = $value->pro;
                $iva = $value->iva;
                $subtotal = $value->subtotal;
                echo json_encode($value);
                $total = $value->total;
                $grantotal = $grantotal +$total;
                self::setDetalles($producto,$nuevoValor,$iva,$subtotal,$total,$folio);
            }
            self::finalVenta($folio,$newDate,$grantotal);
            //actualiza stock
            return array("error"=>false,"message"=>"compra realizada con éxito");
        }else{
            return $errores;
        }  
    }

    private function countProduct($idProducto){
        $conexion = $this->conectar->conecctionDB();
        $sql="select cantidad,nombre from productos where id=? and user=?";
        $sql=$conexion ->prepare($sql);
        $sql->bindValue(1,$idProducto);
        $sql->bindValue(2,$this->id);
        $sql->execute();
        $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    private function restarStock($newCantidad,$idPro){
        $conexion = $this->conectar->conecctionDB();
        $sql="update productos set cantidad=? where id=? and user=?";
        $sql=$conexion->prepare($sql);
        $sql->bindValue(1,$newCantidad);
        $sql->bindValue(2,$idPro);
        $sql->bindValue(3,$this->id);
        $sql->execute();
        return true;
    }
    private function setDetalles($producto,$cantidad,$iva,$sub,$total,$folio){
        $conexion = $this->conectar->conecctionDB();
        $sql="insert into detalle_venta
           values(null,?,?,?,?,?,?,?);";
        $sql=$conexion->prepare($sql);
        $sql->bindValue(1,$producto);
        $sql->bindValue(2,$cantidad);
        $sql->bindValue(3,$iva);
        $sql->bindValue(4,$sub);
        $sql->bindValue(5,$total);
        $sql->bindValue(6,$folio);
        $sql->bindValue(7,$this->id);
        $sql->execute();
        return true;
    }

    private function finalVenta($folio,$fecha,$total){
        $conexion = $this->conectar->conecctionDB();
        $sql="insert into ventas values(null,?,?,?,?);";
        $sql=$conexion->prepare($sql);
        $sql->bindValue(1,$folio);
        $sql->bindValue(2,$fecha);
        $sql->bindValue(3,$total);
        $sql->bindValue(4,$this->id);
        $sql->execute();
        return true;
    }

    /**obtener los detalles */

    public function getDetalles($folio){
        $conexion = $this->conectar->conecctionDB();
        $sql="select * from detalle_venta where folio=? and user=?";
        $sql=$conexion->prepare($sql);
        $sql->bindValue(1,$folio);
        $sql->bindValue(2,$this->id);
        $sql->execute();
        $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }
}