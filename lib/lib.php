<?php

//FILTRADO 

//Función para filtrar los valores recibidos de un formulario
function filtrado($datos)
{
    $datos = trim($datos);                                  // Elimina espacios antes y después de los datos
    $datos = stripslashes($datos);                          // Elimina backslashes \
    $datos = filter_var($datos, FILTER_SANITIZE_STRING);     // Elimina todas las etiquetas    
    return $datos;
}

//ACCESO A BASES DE DATOS

//Conexión a BD
function conectar($basededatos)
{
    /*
    //CONECTAR EN LOCAL
    $MySQL_host = "Localhost";
    $MySQL_user = "root";
    $MySQL_password = "zaio2016";
    try {
        $dsn = "mysql:host=$MySQL_host;dbname=$basededatos";
        $conexion = new PDO($dsn, $MySQL_user,  $MySQL_password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    */
    //HEROKU CLEARDB

    //mysql://b5b63837293cf9:a3b72aad@eu-cdbr-west-03.cleardb.net/heroku_c1ce710b3a14a7d?reconnect=true
    $MySQL_host = "eu-cdbr-west-03.cleardb.net";
    $MySQL_user = "b5b63837293cf9";
    $MySQL_password = "a3b72aad";
    try {
        $dsn = "mysql:host=$MySQL_host;dbname=heroku_c1ce710b3a14a7d";
        $conexion = new PDO($dsn, $MySQL_user,  $MySQL_password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

/*
     *  FUNCIONES PARA LIBROS
     *  ------------------------------------------------------------------
     * */

//Obtener el número de páginas de libros
define("RESPP", 3);

function numPaginas($filtro)
{
    $consulta = "SELECT * FROM libros";
    if (strlen($filtro) > 0) {
        $consulta .= " WHERE titulo LIKE CONCAT('%', :filtro, '%') ";
        $consulta .= " OR subtitulo LIKE CONCAT('%', :filtro, '%')";
        $consulta .= " OR autor LIKE CONCAT('%', :filtro, '%')";
    }
    $conexion = conectar("libreria");
    $stmt = $conexion->prepare($consulta);
    if (strlen($filtro) > 0)
        $stmt->bindParam(":filtro", $filtro);
    $stmt->execute();
    $count = $stmt->rowCount();
    $conexion = null;

    return ceil($count / RESPP);
}

//Obtener todos los libros 
function hacerSelect($filtro, $pagina)
{
    //Resultados por página a mostrar

    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Consulta de todos los libros
        $consulta = "SELECT * FROM libros ";
        if (strlen($filtro) > 0) {
            $consulta .= " WHERE titulo LIKE CONCAT('%', :filtro, '%') ";
            $consulta .= " OR subtitulo LIKE CONCAT('%', :filtro, '%')";
            $consulta .= " OR autor LIKE CONCAT('%', :filtro, '%')";
        }
        //Añadimos la búsqueda a la consulta
        $consulta .= " ORDER BY titulo";
        //Paginador
        if ($pagina > 0) {
            $start = (($pagina - 1) * RESPP);
            $consulta .= " LIMIT " . $start . " , " . RESPP;
        }

        //Preparamos la consulta
        $stmt = $conexion->prepare($consulta);
        if (strlen($filtro) > 0)
            $stmt->bindParam(":filtro", $filtro);
        //Ejecutamos la consulta
        $stmt->execute();
        //Devolvemos los resultados
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }

    return $libros;
}

//Obtener todos los libros 
function selectLibros()
{
    //Resultados por página a mostrar

    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Consulta de todos los libros
        $consulta = "SELECT * FROM libros ";
        //Preparamos la consulta
        $stmt = $conexion->prepare($consulta);
        //Ejecutamos la consulta
        $stmt->execute();
        //Devolvemos los resultados
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }

    return $libros;
}

//Hacer consulta para sacar un libro por ISBN
function hacerSelectISBN($ISBN)
{
    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Consulta sólo del libro por ISBN
        $consulta = "SELECT * FROM libros WHERE ISBN = :ISBN";
        //Preparamos la consulta
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(":ISBN", $ISBN);
        //Ejecutamos la consulta
        $stmt->execute();
        //Devolvemos los resultados
        $libro = $stmt->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }

    return $libro;
}

//Hacer consulta para consultar el numero de libros con ISBN pasado como parametro disponibles 
function stockEjemplarISBN($ISBN)
{
    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Consulta sólo del libro por ISBN
        $consulta = "SELECT ejemplares_disponibles FROM libros WHERE ISBN = :ISBN";
        //Preparamos la consulta
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(":ISBN", $ISBN);
        //Ejecutamos la consulta
        $stmt->execute();
        //Devolvemos los resultados
        $libro = $stmt->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }

    return $libro['ejemplares_disponibles'];
}

//Insertar nuevo libro
function insertarLibro($ISBN, $titulo, $subtitulo, $descripcion, $autor, $editorial, $categoria, $portada, $total_ejemplares, $ejemplares_disponibles)
{
    try {
        //Si el número total de ejemplares es mayor que el de los disponibles  
        if ($total_ejemplares > $ejemplares_disponibles) {
            //Establecer conexión
            $conexion = conectar("libreria");
            //Para evitar problemas con caracteres especiales
            $conexion->query("SET NAMES utf8");
            //Preparamos la consulta
            $consulta = "INSERT INTO libros (ISBN, titulo, subtitulo, descripcion, autor, editorial, 
            categoria, portada, total_ejemplares, ejemplares_disponibles) VALUES (
            :ISBN, :titulo, :subtitulo, :descripcion, :autor, :editorial, :categoria, :portada, 
            :total_ejemplares, :ejemplares_disponibles)";
            $stmt = $conexion->prepare($consulta);

            $stmt->bindParam(':ISBN', $ISBN);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':subtitulo', $subtitulo);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':autor', $autor);
            $stmt->bindParam(':editorial', $editorial);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':portada', $portada);
            $stmt->bindParam(':total_ejemplares', $total_ejemplares);
            $stmt->bindParam(':ejemplares_disponibles', $ejemplares_disponibles);

            $stmt->execute();
            $conexion = null;
        }
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }
}

//Borrar libro
function borrarLibro($ISBN)
{
    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Preparamos la consulta
        $consulta = "DELETE FROM libros WHERE ISBN = :ISBN";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':ISBN', $ISBN);

        $stmt->execute();
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }
}

//Modificar un libro existente
function modificarLibro($ISBN, $titulo, $subtitulo, $descripcion, $autor, $editorial, $categoria, $portada, $total_ejemplares, $ejemplares_disponibles)
{
    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Preparamos la consulta
        $consulta = "UPDATE libros SET titulo=:titulo,subtitulo=:subtitulo,descripcion=:descripcion,autor=:autor,
        editorial=:editorial,categoria=:categoria,portada=:portada,total_ejemplares=:total_ejemplares,
        ejemplares_disponibles=:ejemplares_disponibles ";
        $consulta .= "WHERE ISBN=:ISBN";
        $stmt = $conexion->prepare($consulta);

        $stmt->bindParam(':ISBN', $ISBN);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':subtitulo', $subtitulo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':autor', $autor);
        $stmt->bindParam(':editorial', $editorial);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':portada', $portada);
        $stmt->bindParam(':total_ejemplares', $total_ejemplares);
        $stmt->bindParam(':ejemplares_disponibles', $ejemplares_disponibles);

        $stmt->execute();
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }
}


/*
     *  FUNCIONES PARA USUARIOS
     *  ------------------------------------------------------------------
     * */
function numPaginasUsuarios($filtro)
{
    $consulta = "SELECT * FROM usuarios";
    if (strlen($filtro) > 0) {
        $consulta .= " WHERE DNI = :filtro ";
        $consulta .= " OR apellidos LIKE CONCAT('%', :filtro, '%')";
        $consulta .= " OR nombre LIKE CONCAT('%', :filtro, '%')";
    }
    $conexion = conectar("libreria");
    $stmt = $conexion->prepare($consulta);
    if (strlen($filtro) > 0)
        $stmt->bindParam(":filtro", $filtro);
    $stmt->execute();
    $count = $stmt->rowCount();
    $conexion = null;

    return ceil($count / RESPP);
}

//Obtener todos los usuarios 
function hacerSelectUsuarios($filtro, $pagina)
{
    //Resultados por página a mostrar
    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Consulta de todos los usuarios
        $consulta = "SELECT * FROM usuarios ";
        if (strlen($filtro) > 0) {
            $consulta .= " WHERE DNI = :filtro ";
            $consulta .= " OR apellidos LIKE CONCAT('%', :filtro, '%')";
            $consulta .= " OR nombre LIKE CONCAT('%', :filtro, '%')";
        }
        //Añadimos la búsqueda a la consulta
        $consulta .= " ORDER BY apellidos";
        //Paginador
        if ($pagina > 0) {
            $start = (($pagina - 1) * RESPP);
            $consulta .= " LIMIT " . $start . " , " . RESPP;
        }

        //Preparamos la consulta
        $stmt = $conexion->prepare($consulta);
        if (strlen($filtro) > 0)
            $stmt->bindParam(":filtro", $filtro);
        //Ejecutamos la consulta
        $stmt->execute();
        //Devolvemos los resultados
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }

    return $libros;
}

//Obtener todos los usuarios 
function selectUsuarios()
{
    //Resultados por página a mostrar

    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Consulta de todos los libros
        $consulta = "SELECT * FROM usuarios ";
        //Preparamos la consulta
        $stmt = $conexion->prepare($consulta);
        //Ejecutamos la consulta
        $stmt->execute();
        //Devolvemos los resultados
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }

    return $libros;
}

//Insertar nuevo usuario
function insertarUsuario($DNI, $nombre, $apellidos, $edad, $direccion, $poblacion, $telefono, $email)
{
    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Preparamos la consulta
        $consulta = "INSERT INTO usuarios (DNI, nombre, apellidos, edad, direccion, poblacion, 
        telefono, email) VALUES (:DNI, :nombre, :apellidos, :edad, :direccion, :poblacion, :telefono, :email)";
        $stmt = $conexion->prepare($consulta);

        $stmt->bindParam(':DNI', $DNI);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':poblacion', $poblacion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);

        $stmt->execute();
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }
}

//Borrar usuario
function borrarUsuario($DNI)
{
    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Preparamos la consulta
        $consulta = "DELETE FROM usuarios WHERE DNI = :DNI";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':DNI', $DNI);

        $stmt->execute();
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }
}

//Hacer consulta para sacar un usuario por DNI
function hacerSelectDNI($DNI)
{
    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Consulta sólo del usuario por DNI
        $consulta = "SELECT * FROM usuarios WHERE DNI = :DNI";
        //Preparamos la consulta
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(":DNI", $DNI);
        //Ejecutamos la consulta
        $stmt->execute();
        //Devolvemos los resultados
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }

    return $usuario;
}

//Modificar un usuario existente
function modificarUsuario($DNI, $nombre, $apellidos, $edad, $direccion, $poblacion, $telefono, $email)
{
    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Preparamos la consulta
        $consulta = "UPDATE usuarios SET nombre=:nombre,apellidos=:apellidos,edad=:edad,direccion=:direccion,
        poblacion=:poblacion,telefono=:telefono,email=:email ";
        $consulta .= "WHERE DNI=:DNI";
        $stmt = $conexion->prepare($consulta);

        $stmt->bindParam(':DNI', $DNI);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':poblacion', $poblacion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);

        $stmt->execute();
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }
}

/*
     *  FUNCIONES PARA PRESTAMOS (LIBROS-USUARIOS)
     *  ------------------------------------------------------------------
     * */

//Obtener todos los prestamos 
function hacerSelectPrestamos($filtro, $pagina)
{
    //Resultados por página a mostrar
    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Consulta de todos los prestamos
        $consulta = "SELECT * FROM prestamos ";
        if (strlen($filtro) > 0) {
            $consulta .= " WHERE DNI = :filtro ";
            $consulta .= " OR estado LIKE CONCAT('%', :filtro, '%')";
        }
        //Añadimos la búsqueda a la consulta
        $consulta .= " ORDER BY DNI";
        //Paginador
        if ($pagina > 0) {
            $start = (($pagina - 1) * RESPP);
            $consulta .= " LIMIT " . $start . " , " . RESPP;
        }

        //Preparamos la consulta
        $stmt = $conexion->prepare($consulta);
        if (strlen($filtro) > 0)
            $stmt->bindParam(":filtro", $filtro);
        //Ejecutamos la consulta
        $stmt->execute();
        //Devolvemos los resultados
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }
    return $libros;
}

//Obtener todos los prestamos 
function selectPrestamos()
{
    //Resultados por página a mostrar

    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Consulta de todos los libros
        $consulta = "SELECT * FROM prestamos ";
        //Preparamos la consulta
        $stmt = $conexion->prepare($consulta);
        //Ejecutamos la consulta
        $stmt->execute();
        //Devolvemos los resultados
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }

    return $libros;
}


//Insertar nuevo prestamo
function insertarPrestamo($ISBN, $DNI, $fecha_inicio, $fecha_fin, $estado)
{
    try {
        //Compruebo que hay libros disponibles 
        if (stockEjemplarISBN($ISBN) > 0 && $fecha_inicio < $fecha_fin) {
            //Establecer conexión
            $conexion = conectar("libreria");
            //Para evitar problemas con caracteres especiales
            $conexion->query("SET NAMES utf8");
            //Preparamos la consulta
            $consulta = "INSERT INTO prestamos (ISBN, DNI, fecha_inicio, fecha_fin, estado) 
            VALUES (:ISBN, :DNI, :fecha_inicio, :fecha_fin, :estado) ";
            $stmt = $conexion->prepare($consulta);

            $stmt->bindParam(':ISBN', $ISBN);
            $stmt->bindParam(':DNI', $DNI);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->bindParam(':estado', $estado);

            $stmt->execute();
            $conexion = null;
        }
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }
}

//Borrar prestamo
function borrarPrestamo($ISBN, $DNI)
{
    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Preparamos la consulta
        $consulta = "DELETE FROM prestamos WHERE ISBN = :ISBN AND DNI = :DNI";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':ISBN', $ISBN);
        $stmt->bindParam(':DNI', $DNI);

        $stmt->execute();
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }
}

//Hacer consulta para sacar un prestamo por DNI y ISBN
function hacerSelectISBN_DNI($ISBN, $DNI)
{
    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Consulta sólo del prestamo por DNI y ISBN
        $consulta = "SELECT * FROM prestamos WHERE ISBN = :ISBN AND DNI = :DNI";
        //Preparamos la consulta
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(":ISBN", $ISBN);
        $stmt->bindParam(":DNI", $DNI);
        //Ejecutamos la consulta
        $stmt->execute();
        //Devolvemos los resultados
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }

    return $usuario;
}

//Modificar un prestamo existente
function modificarPrestamo($ISBN, $DNI, $fecha_fin, $estado)
{
    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Preparamos la consulta
        $consulta = "UPDATE prestamos SET fecha_fin=:fecha_fin,estado=:estado WHERE ISBN = :ISBN AND DNI=:DNI";
        $stmt = $conexion->prepare($consulta);

        $stmt->bindParam(':ISBN', $ISBN);
        $stmt->bindParam(':DNI', $DNI);
        $stmt->bindParam(':fecha_fin', $fecha_fin);
        $stmt->bindParam(':estado', $estado);

        $stmt->execute();
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }
}









function obtenerEmpleadosPorIdProyecto($id)
{

    $empleados = null;

    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Consulta de todos los empleados
        $consulta = "SELECT empleados.nombre, trabaja.fechaInicio, trabaja.fechaFin, trabaja.puesto, ";
        $consulta .= " trabaja.id_empleado, trabaja.id_proyecto FROM trabaja ";
        $consulta .= " LEFT JOIN (empleados,proyectos) ";
        $consulta .= " ON (trabaja.id_empleado = empleados.id AND ";
        $consulta .= " trabaja.id_proyecto = proyectos.id) ";
        $consulta .= " WHERE trabaja.id_proyecto = :id ";
        $consulta .= " AND trabaja.fechaInicio > proyectos.fechaInicio";
        //$consulta .= " AND trabaja.fechaFin < proyectos.fechaFinPrevista";

        //Preparamos la consulta
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(":id", $id);
        //Ejecutamos la consulta
        $stmt->execute();
        //Devolvemos los resultados
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
    } catch (PDOException $e) {
        //file_put_contents("bd.log",$e->getMessage(), FILE_APPEND | LOCK_EX);
        echo $e->getMessage();
    }

    return $empleados;
}

//Borrar participante de un proyecto en una fecha
function deleteParticipante($idEmpleado, $idProyecto, $fechaInicio)
{
    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Preparamos la consulta
        $consulta = "DELETE FROM trabaja WHERE id_empleado = :id_empleado AND id_proyecto = :id_proyecto AND fechaInicio = :fechaInicio";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id_empleado', $idEmpleado);
        $stmt->bindParam(':id_proyecto', $idProyecto);
        $stmt->bindParam(':fechaInicio', $fechaInicio);

        $stmt->execute();
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }
}

//Obtener todos los empleados de la empresa
//Hacer consulta
function obtenerEmpleados()
{
    //Resultados por página a mostrar

    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Consulta de todos los empleados
        $consulta = "SELECT id,nombre,apellidos,dni FROM empleados ";
        //Añadimos la búsqueda a la consulta
        $consulta .= " ORDER BY apellidos";

        //Preparamos la consulta
        $stmt = $conexion->prepare($consulta);

        //Ejecutamos la consulta
        $stmt->execute();

        //Devolvemos los resultados
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
    }

    return $empleados;
}

//Añadir empleado a un proyecto
function addEmpleadoProyecto($idProyecto, $idEmpleado, $fechaInicio, $puesto)
{
    try {
        //Establecer conexión
        $conexion = conectar("libreria");
        //Para evitar problemas con caracteres especiales
        $conexion->query("SET NAMES utf8");
        //Preparamos la consulta
        $consulta = "INSERT INTO trabaja (id_proyecto,id_empleado,fechaInicio,puesto) VALUES (";
        $consulta .= ":id_proyecto, :id_empleado, :fechaInicio, :puesto)";
        $stmt = $conexion->prepare($consulta);

        $stmt->bindParam(':id_proyecto', $idProyecto);
        $stmt->bindParam(':id_empleado', $idEmpleado);
        $stmt->bindParam(':fechaInicio', $fechaInicio);
        $stmt->bindParam(':puesto', $puesto);

        $stmt->execute();
        $conexion = null;
    } catch (PDOException $e) {
        file_put_contents("bd.log", $e->getMessage(), FILE_APPEND | LOCK_EX);
        //echo $e->getMessage();
        if (strstr($e->getMessage(), "Duplicate entry"))
            return "Duplicada";
    }
}
