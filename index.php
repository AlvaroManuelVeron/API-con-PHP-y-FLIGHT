<?php

require 'flight/Flight.php'; // este es el framework flight

Flight::register('db','PDO',array('mysql:host=localhost;dbname=api','root','')); //conectamos la base de datos


//GET: Lee los datos y los muestra a cualquier interfaz que solicita dichos datos
Flight::route('GET /alumnos', function () { // aca tenemos el FRAMEWORK (Flight), LA RUTA (route), EL METODOS (GET), LA URL Y LA FUNCION(Function)
    $sentencia= Flight::db()->prepare("SELECT * FROM `alumnos`");
    $sentencia->execute();
    $datos=$sentencia->fetchAll();
    Flight::json($datos);
});


//POST: Recepciona los datos y los inserta
Flight::route('POST /alumnos', function () {

    $nombres=(Flight::request()->data->nombres);//recibe una variable que viene de un metodo POST, lo envia a una URL "alumnos" y recepciona atraves del "request()->data" que los almacenamos en la variable $nombres y lo imprimimos
    $apellidos=(Flight::request()->data->apellidos);

    $sql="INSERT INTO alumnos (nombres,apellidos) VALUES(?,?)"; //esta INTRUCCION SQL nos permite insertar dentro de la tabla "alumnos" los nombres y apellidos con los valores que vamos a recepcionar
    $sentencia= Flight::db()->prepare($sql); //se ejecuta y se pasa los dos parametros
    $sentencia->bindParam(1,$nombres);
    $sentencia->bindParam(2,$apellidos);
    $sentencia->execute();//ejecutamos

    Flight::jsonp(["Alumno agregado"]);//enviamos un JSON


});

//DELETE: Borrar los registros
Flight::route('DELETE /alumnos', function () {
    $id=(Flight::request()->data->id);
    $sql="DELETE FROM alumnos WHERE id=?";
    $sentencia= Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$id);
    $sentencia->execute(); 

    Flight::jsonp(["Alumno borrado"]);


});


//PUT: Actualizar registros
Flight::route('PUT /alumnos', function () {

    $id=(Flight::request()->data->id);
    $nombres=(Flight::request()->data->nombres);
    $apellidos=(Flight::request()->data->apellidos);

    $sql="UPDATE alumnos SET nombres=? ,apellidos=? WHERE id=?";

    $sentencia= Flight::db()->prepare($sql);

    $sentencia->bindParam(1,$nombres);
    $sentencia->bindParam(2,$apellidos);
    $sentencia->bindParam(3,$id);

    $sentencia->execute(); 

    Flight::jsonp(["Alumno modificado"]);

});

//GET DETERMINADO: Lectura de un registro determinado
Flight::route('GET /alumnos/@id', function ($id) {
    $sentencia= Flight::db()->prepare("SELECT * FROM `alumnos` WHERE id=?");
    $sentencia->bindParam(1,$id);

    $sentencia->execute(); 
    $datos=$sentencia->fetchAll();
    Flight::json($datos);



});

Flight::start();
