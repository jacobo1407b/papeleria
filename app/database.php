<?php 
//include 'config/credenciales.php'; //Nuestras credenciales de base de datos

/*$capsule = new Capsule();
$capsule->addConnection([
    "driver"    => "mysql",
    "host"      => $db_host,
    "database"  => $db_name,
    "username"  => $db_user,
    "password"  => $db_pass,
    "charset"   => "utf8",
    "collation" => "utf8_general_ci",
    "prefix"    => ""
]);*/

class db{
    public function conecctionDB(){
        try {
            $databse = getenv('DBNAME');
            $userdb=getenv('USERDB');
            $passwordSql=getenv('PASSWORD');
            $conectar = $this->dbh = new PDO("mysql:local=localhost;dbname=papeleria","root","");
         
            return $conectar;
           
         } catch (Exception $e) {

           print "¡Error!: " . $e->getMessage() . "<br/>";
           die();  
           
         }
    }
    public function set_names(){

        return $this->dbh->query("SET NAMES 'utf8'");
    }
}