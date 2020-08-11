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
//Dta base: PAPELERIADANI
// DTA BASE USER: J1407B
//PASSWORD:6@\dRKhB}Dd_Os9T

//DB Name:id14581626_papeleriadani
//DB User:	id14581626_j1407b
//host : localhost


class db{
    public function conecctionDB(){
        try {
            $dsn = "/cloudsql/coastal-volt-275801:us-east4:papeleria-dani";
            $user = "papeleria-dani";
            $password = "papeleria-dani123";
            //PDO($dsn, $user, $password);
            //$dsn = sprintf('mysql:dbname=%s;host=%s', 'papeleria-dani', '35.245.250.197');
            //PDO("mysql:host=35.245.250.197;dbname=papeleria-dani","","");
            //
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