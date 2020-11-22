<?php

/*
     * FUNCIONES
     */

//Funciones de acceso a BD
include_once("lib/lib.php");


/*
     * CONTROLADOR
     */

//// ACCIONES PARA LIBROS
// -----------------------------------------------------------

//Acción de INSERTAR un libro
if (isset($_POST['add_libro'])) {
    //Recibimos todos los datos del empleado y filtramos la entrada
    $ISBN = filtrado($_POST['isbn']);
    $titulo = filtrado($_POST['titulo']);
    $subtitulo = filtrado($_POST['subtitulo']);
    $descripcion = filtrado($_POST['descripcion']);
    $autor = filtrado($_POST['autor']);
    $editorial = filtrado($_POST['editorial']);
    $categoria = filtrado($_POST['categoria']);
    $portada = $_POST['portada'];
    $total_ejemplares = filtrado($_POST['total_ejemplares']);
    $ejemplares_disponibles = filtrado($_POST['ejemplares_disponibles']);

    /*
        //Recibo la imagen y la guardo
        if (isset($_FILES['portada'])) {
            //Faltarían comprobaciones de la imagen
            $imagen = $_FILES['imagen']['tmp_name'];
            $portada = file_get_contents($imagen);
        }
        */

    //Conectar a BD y hacer insert
    insertarLibro($ISBN, $titulo, $subtitulo, $descripcion, $autor, $editorial, $categoria, $portada, $total_ejemplares, $ejemplares_disponibles);

    //Si todo ha ido bien, redirigimos a index.php
    $url = "Location: index.php?accion=add_libro";

    //Redirigimos a index.php con 
    header($url);
}

//Acción de BORRAR un libro
if (isset($_GET['delete_libro'])) {
    //Recibimos todos los datos del empleado y filtramos la entrada
    $ISBN = filtrado($_GET['delete_libro']);

    //Conectar a BD y hacer delete
    borrarLibro($ISBN);

    //Si todo ha ido bien, redirigimos a index.php
    $url = "Location: index.php?accion=delete_libro";

    //Redirigimos a index.php con 
    header($url);
}

//Acción de RECUPERAR libro para MODIFICARLO
if (isset($_GET['update_libro'])) {
    //Recibimos todos los datos del libro y filtramos la entrada
    $ISBN = filtrado($_GET['update_libro']);

    //Recuperar los datos de ese empleado para mostrarlos después
    $libro = hacerSelectISBN($ISBN);

    //Si todo ha ido bien, redirigimos a index.php
    $url = "Location: index.php?accion=update_libro&ISBN=" . $libro['ISBN'];
    $url .= "&titulo=" . $libro['titulo'];
    $url .= "&subtitulo=" . $libro['subtitulo'];
    $url .= "&descripcion=" . $libro['descripcion'];
    $url .= "&autor=" . $libro['autor'];
    $url .= "&editorial=" . $libro['editorial'];
    $url .= "&categoria=" . $libro['categoria'];
    $url .= "&portada=" . $libro['portada'];
    $url .= "&total_ejemplares=" . $libro['total_ejemplares'];
    $url .= "&ejemplares_disponibles=" . $libro['ejemplares_disponibles'];
    //Redirigimos al index con 
    header($url);
}

//Acción de MODIFICAR el libro
if (isset($_POST['update_libro'])) {
    //Recibimos todos los datos del libro y filtramos la entrada
    $ISBN = filtrado($_POST['isbn']);
    $titulo = filtrado($_POST['titulo']);
    $subtitulo = filtrado($_POST['subtitulo']);
    $descripcion = filtrado($_POST['descripcion']);
    $autor = filtrado($_POST['autor']);
    $editorial = filtrado($_POST['editorial']);
    $categoria = filtrado($_POST['categoria']);
    $portada = $_POST['portada'];
    $total_ejemplares = filtrado($_POST['total_ejemplares']);
    $ejemplares_disponibles = filtrado($_POST['ejemplares_disponibles']);

    modificarLibro($ISBN, $titulo, $subtitulo, $descripcion, $autor, $editorial, $categoria, $portada, $total_ejemplares, $ejemplares_disponibles);

    //Si todo ha ido bien, redirigimos a index.php
    $url = "Location: index.php?accion=modificado_libro";

    //Redirigimos a index.php con 
    header($url);
}

//// ACCIONES PARA USUARIOS
// -----------------------------------------------------------

//Acción de INSERTAR un usuario
if (isset($_POST['add_usuario'])) {
    //Recibimos todos los datos del usuario y filtramos la entrada
    $DNI = filtrado($_POST['dni']);
    $nombre = filtrado($_POST['nombre']);
    $apellidos = filtrado($_POST['apellidos']);
    $edad = filtrado($_POST['edad']);
    $direccion = filtrado($_POST['direccion']);
    $poblacion = filtrado($_POST['poblacion']);
    $telefono = filtrado($_POST['telefono']);
    $email = filtrado($_POST['email']);

    //Conectar a BD y hacer insert
    insertarUsuario($DNI, $nombre, $apellidos, $edad, $direccion, $poblacion, $telefono, $email);

    //Si todo ha ido bien, redirigimos a index.php
    $url = "Location: index.php?accion=add_usuario";

    //Redirigimos a index.php con 
    header($url);
}

//Acción de BORRAR un usuario
if (isset($_GET['delete_usuario'])) {
    //Recibimos todos los datos del empleado y filtramos la entrada
    $DNI = filtrado($_GET['delete_usuario']);

    //Conectar a BD y hacer delete
    borrarUsuario($DNI);

    //Si todo ha ido bien, redirigimos a index.php
    $url = "Location: index.php?accion=delete_usuario";

    //Redirigimos a index.php con 
    header($url);
}

//Acción de RECUPERAR usuario para MODIFICARLO
if (isset($_GET['update_usuario'])) {
    //Recibimos todos los datos del libro y filtramos la entrada
    $DNI = filtrado($_GET['update_usuario']);

    //Recuperar los datos de ese usuario para mostrarlos después
    $usuario = hacerSelectDNI($DNI);

    //Si todo ha ido bien, redirigimos a index.php
    $url = "Location: index.php?accion=update_usuarios&DNI=" . $usuario['DNI'];
    $url .= "&nombre=" . $usuario['nombre'];
    $url .= "&apellidos=" . $usuario['apellidos'];
    $url .= "&edad=" . $usuario['edad'];
    $url .= "&direccion=" . $usuario['direccion'];
    $url .= "&poblacion=" . $usuario['poblacion'];
    $url .= "&telefono=" . $usuario['telefono'];
    $url .= "&email=" . $usuario['email'];
    //Redirigimos a index.php con 
    header($url);
}

//Acción de MODIFICAR el usuario
if (isset($_POST['update_usuario'])) {
    //Recibimos todos los datos del usuario y filtramos la entrada
    $DNI = filtrado($_POST['dni']);
    $nombre = filtrado($_POST['nombre']);
    $apellidos = filtrado($_POST['apellidos']);
    $edad = filtrado($_POST['edad']);
    $direccion = filtrado($_POST['direccion']);
    $poblacion = filtrado($_POST['poblacion']);
    $telefono = filtrado($_POST['telefono']);
    $email = filtrado($_POST['email']);

    modificarUsuario($DNI, $nombre, $apellidos, $edad, $direccion, $poblacion, $telefono, $email);

    //Si todo ha ido bien, redirigimos a index.php
    $url = "Location: index.php?accion=modificado_usuario";

    //Redirigimos a index.php con 
    header($url);
}

//// ACCIONES PARA PRESTAMOS
// -----------------------------------------------------------
//Acción de INSERTAR un Prestamo
if (isset($_POST['add_prestamo'])) {
    //Recibimos todos los datos del PRESTAMO y filtramos la entrada
    $ISBN = filtrado($_POST['isbn']);
    $DNI = filtrado($_POST['dni']);
    $fecha_inicio = filtrado($_POST['fecha_inicio']);
    $fecha_fin = filtrado($_POST['fecha_fin']);
    $estado = filtrado($_POST['estado']);

    //Conectar a BD y hacer insert
    insertarPrestamo($ISBN, $DNI, $fecha_inicio, $fecha_fin, $estado);

    //Si todo ha ido bien, redirigimos a index.php
    $url = "Location: index.php?accion=add_prestamo";

    //Redirigimos a index.php con 
    header($url);
}

//Acción de BORRAR un prestamos
if (isset($_GET['delete_prestamo'])) {
    $ISBN = filtrado(explode("-", $_GET['delete_prestamo'])[0]);
    $DNI = filtrado(explode("-", $_GET['delete_prestamo'])[1]);

    //Conectar a BD y hacer delete
    borrarPrestamo($ISBN, $DNI);

    //Si todo ha ido bien, redirigimos a index.php
    $url = "Location: index.php?accion=delete_prestamo";

    //Redirigimos a index.php con 
    header($url);
}

//Acción de RECUPERAR prestamo para MODIFICARLO
if (isset($_GET['update_prestamo'])) {
    //Recibimos todos los datos del prestamo y filtramos la entrada
    $ISBN = filtrado(explode("-", $_GET['update_prestamo'])[0]);
    $DNI = filtrado(explode("-", $_GET['update_prestamo'])[1]);

    //Recuperar los datos de ese prestamo para mostrarlos después
    $prestamo = hacerSelectISBN_DNI($ISBN, $DNI);

    //Si todo ha ido bien, redirigimos a index.php
    $url = "Location: index.php?accion=update_prestamo&ISBN=" . $prestamo['ISBN'];
    $url .= "&DNI=" . $prestamo['DNI'];
    $url .= "&fecha_fin=" . $prestamo['fecha_fin'];
    $url .= "&estado=" . $prestamo['estado'];

    //Redirigimos a index.php con 
    header($url);
}

//Acción de MODIFICAR el prestamo
if (isset($_POST['update_prestamo'])) {
    //Recibimos todos los datos del prestamo y filtramos la entrada
    $ISBN = filtrado($_POST['isbn']);
    $DNI = filtrado($_POST['dni']);
    $fecha_fin = filtrado($_POST['fecha_fin']);
    $estado = filtrado($_POST['estado']);

    modificarPrestamo($ISBN, $DNI, $fecha_fin, $estado);

    //Si todo ha ido bien, redirigimos a index.php
    $url = "Location: index.php?accion=modificado_prestamo";

    //Redirigimos a index.php con 
    header($url);
}

/**
 *   GENERAR CSV PRAR LIBROS Y PARA USUARIOS
 */

if (isset($_GET['accion'])) {
    if ($_GET['accion'] == "csvLibros") {
        $libros = selectLibros();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="libros.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        $output = fopen("php://output", "w");
        fputcsv($output, array("ISBN", "Titulo", "Subtitulo", "Descripcion", "Autor", "Editorial", "Categoria", "Portada", "Total Ejemplares", "Ejemplares Disponibles"));
        foreach ($libros as $libro) {
            fputcsv($output, $libro);
        }
        fclose($output);
        die;
    }

    if ($_GET['accion'] == "csvUsuarios") {
        $usuarios = selectUsuarios();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="usuarios.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        $output = fopen("php://output", "w");
        fputcsv($output, array("DNI", "Nombre", "Apellidos", "Edad", "Dirección", "Población", "Teléfono", "Email"));
        foreach ($usuarios as $usuario) {
            fputcsv($output, $usuario);
        }
        fclose($output);
        die;
    }
}
