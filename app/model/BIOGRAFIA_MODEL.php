<?php



class Biografia  {
    public function __construct($id){
        $this->id = $id;
        $this->conectar = new db();
    }

    public function getMono($pagina){
        $conexion = $this->conectar->conecctionDB();
        $salto = ((9* $pagina)-9);
        $sql="select * from biografia where user=?  LIMIT 9 OFFSET $salto";
        $sql=$conexion->prepare($sql);
        $sql->bindValue(1,$this->id);
        $sql->execute();
        $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
        $pages = self::contar();
        return array("data"=>$resultado,"paginas"=>$pages);
    }
    private function contar(){
        $conexion = $this->conectar->conecctionDB();
        $sql="select * from biografia where user=?";
        $sql=$conexion ->prepare($sql);
        $sql->bindValue(1,$this->id);
        $sql->execute();
        $cou=$sql->rowCount();
        $paginas = $cou/9;
        return ceil($paginas); 
    }

    public function agregarBiografia($nombre){
        $conexion = $this->conectar->conecctionDB();
        $sql="insert into biografia
           values(null,?,?);";
        $sql=$conexion->prepare($sql);
        $sql->bindValue(1,$nombre);
        $sql->bindValue(2,$this->id);
        if($sql->execute()){
            return array("message" => "Registro agregado con éxito","error"=>false);
        }else{
            return array("message" => "Error en la consulta","error"=>true);
        }
    }

    public function editarBio($nombre,$idBio){
        $conexion = $this->conectar->conecctionDB();
        $sql="update biografia set nombre=? where id=? and user=?";
        $sql=$conexion->prepare($sql);
        $sql->bindValue(1,$nombre);
        $sql->bindValue(2,$idBio);
        $sql->bindValue(3,$this->id);
        if($sql->execute()){
            return array("message" => "Actualización con éxito","error"=>false);
        }else{
            return array("message" => "Error al actualizar","error"=>true);
        }
    }

    public function deleteBio($id){
        $conexion = $this->conectar->conecctionDB();
        $sql="delete from biografia where id =? and user=?";
        $sql=$conexion->prepare($sql);
        $sql->bindValue(1,$id);
        $sql->bindValue(2,$this->id);
        if($sql->execute()){
            return array("message" => "Registro eliminado","error"=>false);
        }else{
            return array("message" => "Error en la consulta","error"=>true);
        }
    }
}