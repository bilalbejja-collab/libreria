<?php
include_once("lib/cabecera.php");
include_once("lib/lib.php");
?>

<body style="background-color: black;">
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="index.php" style="font-weight: bold; color: white;">&nbsp;L&nbsp;I&nbsp;B&nbsp;R&nbsp;E&nbsp;R&nbsp;Í&nbsp;A</a>
			</div>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="#" style="font-weight: bold; color: white;">Administrador &nbsp;<i style='font-size:20px' class='fas'>&#xf0d7;</i></a></li>
			</ul>
		</div>
	</nav>
	<div class="row">
		<div class="col-2">
			<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
				<a class="nav-link" id="v-pills-libros-tab" style="background-color: black; color: gray; margin-top: 10%;" data-toggle="pill" href="#v-pills-libros" role="tab" aria-controls="v-pills-libros" aria-selected="true"><i style='font-size:15px' class='fas'>&#xf518;</i>&nbsp;&nbsp;LIBROS</a>
				<a class="nav-link" id="v-pills-usuarios-tab" style="background-color: black; color: gray;" data-toggle="pill" href="#v-pills-usuarios" role="tab" aria-controls="v-pills-usuarios" aria-selected="false"><i style='font-size:15px' class='fas'>&#xf0c0;</i>&nbsp;USUARIOS</a>
				<a class="nav-link" id="v-pills-prestamos-tab" style="background-color: black; color: gray;" data-toggle="pill" href="#v-pills-prestamos" role="tab" aria-controls="v-pills-prestamos" aria-selected="false">&nbsp;<i style='font-size:15px' class='fab'>&#xf3ca;</i>&nbsp;&nbsp;PRESTAMOS</a>
			</div>
		</div>
		<div class="col-10">
			<div class="tab-content" id="v-pills-tabContent">
				<!--LIBROS-->
				<div class="tab-pane fade show" id="v-pills-libros" role="tabpanel" aria-labelledby="v-pills-libros-tab">
					<div class="container mt-5">
						<div class="table-wrapper">
							<div class="table-title">
								<div class="row">
									<div class="col-sm-4">
										<h2>LIBROS</h2>
									</div>
									<div class="col-sm-8">
										<a href="#addLibroModal" class="btn btn-success" data-toggle="modal"><span>Añadir Libro</span></a>
										<a href="controlador.php?accion=csvLibros" class="btn btn-danger"> <span>Generar CSV</span></a>
									</div>
								</div>
								<div class="row float-right">
									<form action='index.php' method='get'>
										<div class='row form-group mb-2 pr-2'>
											<label class="col-sm-2"> Filtro: </label>
											<input class="col-sm-6" type="text" name="filtro" class="form-control">
											<input class="col-sm-3 ml-2 btn btn-primary" type='submit' name='buscarLibro' value='Buscar'>
										</div>
									</form>
								</div>
							</div>

							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>ISBN</th>
										<th>TÍTULO</th>
										<th>SUBTÍTULO</th>
										<th>DESCRIPCIÓN</th>
										<th>AUTOR</th>
										<th>EDITORIAL</th>
										<th>CATEGORÍA</th>
										<th>PORTADA</th>
										<th>NÚM. EJEMP. TOTAL.</th>
										<th>NÚM. EJEMP. DISP.</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
									//Comprobar si hemos pulsado el filtro de búsqueda
									$filtro = "";
									if (isset($_GET['filtro'])) {
										$filtro = filtrado($_GET['filtro']);
									}

									//Paginador
									if (isset($_GET['pagina'])) {
										$pagina = $_GET['pagina'];
									} else {
										$pagina = 1;
									}

									//Mostramos los Libros desde la BD
									$libros = hacerSelect($filtro, $pagina);

									//Recorremos los resultados
									foreach ($libros as $libro) {
									?>
										<tr>
											<td><?php echo $libro['ISBN']; ?></td>
											<td><?php echo $libro['titulo']; ?></td>
											<td><?php echo $libro['subtitulo']; ?></td>
											<td><?php echo $libro['descripcion']; ?></td>
											<td><?php echo $libro['autor']; ?></td>
											<td><?php echo $libro['editorial']; ?></td>
											<td><?php echo $libro['categoria']; ?></td>
											<!--<td><img style='width: 100px;' src='data:image/jpg;base64,' . base64_encode($pelicula['portada']) . ''></td>-->
											<td><img class="portada" src="<?php echo $libro['portada']; ?>" style='width: 100px;'></td>
											<td><?php echo $libro['total_ejemplares']; ?></td>
											<td><?php echo $libro['ejemplares_disponibles']; ?></td>
											<td>

												<a href="controlador.php?update_libro=<?php echo $libro['ISBN']; ?>"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
												<a href="controlador.php?delete_libro=<?php echo $libro['ISBN']; ?>"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
											</td>
										</tr>
									<?php
									}

									?>
								</tbody>
								<tfooter>
									<tr style="text-align: center;">
										<td colspan='12'>
											<?php
											//Calculamos el número total de páginas consultando BD
											$np = numPaginas($filtro);

											?>
											<a href="index.php?filtro=<?php echo $filtro; ?>&pagina=<?php if ($pagina > 1) echo ($pagina - 1);
																									else echo 1; ?>&accion=paginador_libro">
												<<</a> <a href="index.php?filtro=<?php echo $filtro; ?>&pagina=<?php if ($pagina < $np) echo ($pagina + 1);
																												else echo $np; ?>&accion=paginador_libro">&nbsp;&nbsp;&nbsp;>>
											</a>
										</td>
									</tr>

								</tfooter>
							</table>


						</div>
					</div>
				</div>
				<!--USUARIOS-->
				<div class="tab-pane fade" id="v-pills-usuarios" role="tabpanel" aria-labelledby="v-pills-usuarios-tab">
					<div class="container mt-5">
						<div class="table-wrapper">
							<div class="table-title">
								<div class="row">
									<div class="col-sm-4">
										<h2>USUARIOS</h2>
									</div>
									<div class="col-sm-8">
										<a href="#addUsuarioModal" class="btn btn-success" data-toggle="modal"><span>Añadir Usuario</span></a>
										<a href="controlador.php?accion=csvUsuarios" class="btn btn-danger"> <span>Generar CSV</span></a>
									</div>
								</div>
								<div class="row float-right">
									<form action='index.php' method='get'>
										<div class='row form-group mb-2 pr-2'>
											<label class="col-sm-2"> Filtro: </label>
											<input class="col-sm-6" type="text" name="filtro" class="form-control">
											<input class="col-sm-3 ml-2 btn btn-primary" type='submit' name='buscarUsuario' value='Buscar'>
										</div>
									</form>
								</div>
							</div>

							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>DNI</th>
										<th>NOMRBE</th>
										<th>APELLIDOS</th>
										<th>EDAD</th>
										<th>DIRECCIÓN</th>
										<th>POBLACIÓN</th>
										<th>TELÉFONO</th>
										<th>EMAIL</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
									//Comprobar si hemos pulsado el filtro de búsqueda
									$filtro = "";
									if (isset($_GET['filtro'])) {
										$filtro = filtrado($_GET['filtro']);
									}

									//Paginador
									if (isset($_GET['pagina'])) {
										$pagina = $_GET['pagina'];
									} else {
										$pagina = 1;
									}

									//Mostramos los Libros desde la BD
									$usuarios = hacerSelectUsuarios($filtro, $pagina);

									//Recorremos los resultados
									foreach ($usuarios as $usuario) {
									?>
										<tr>
											<td><?php echo $usuario['DNI']; ?></td>
											<td><?php echo $usuario['nombre']; ?></td>
											<td><?php echo $usuario['apellidos']; ?></td>
											<td><?php echo $usuario['edad']; ?></td>
											<td><?php echo $usuario['direccion']; ?></td>
											<td><?php echo $usuario['poblacion']; ?></td>
											<td><?php echo $usuario['telefono']; ?></td>
											<td><?php echo $usuario['email']; ?></td>
											<td>

												<a href="controlador.php?update_usuario=<?php echo $usuario['DNI']; ?>"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
												<a href="controlador.php?delete_usuario=<?php echo $usuario['DNI']; ?>"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
											</td>
										</tr>
									<?php
									}

									?>
								</tbody>
								<tfooter>
									<tr style="text-align: center;">
										<td colspan='12'>
											<?php
											//Calculamos el número total de páginas consultando BD
											$np = numPaginasUsuarios($filtro);

											?>
											<a href="index.php?filtro=<?php echo $filtro; ?>&pagina=<?php if ($pagina > 1) echo ($pagina - 1);
																									else echo 1; ?>&accion=paginador_usuario">
												<< </a> <a href="index.php?filtro=<?php echo $filtro; ?>&pagina=<?php if ($pagina < $np) echo ($pagina + 1);
																												else echo $np; ?>&accion=paginador_usuario">&nbsp;&nbsp;&nbsp;>>
											</a>
										</td>
									</tr>

								</tfooter>
							</table>


						</div>
					</div>
				</div>
				<!--PRÉSTAMOS-->
				<div class="tab-pane fade" id="v-pills-prestamos" role="tabpanel" aria-labelledby="v-pills-prestamos-tab">
					<div class="container mt-5">
						<div class="table-wrapper">
							<div class="table-title">
								<div class="row">
									<div class="col-sm-4">
										<h2>PRÉSTAMOS</h2>
									</div>
									<div class="col-sm-8">
										<a href="#addPrestamoModal" class="btn btn-success" data-toggle="modal"><span>Añadir Préstamo</span></a>
									</div>
								</div>
								<div class="row float-right">
									<form action='index.php' method='get'>
										<div class='row form-group mb-2 pr-2'>
											<label class="col-sm-2"> Filtro: </label>
											<input class="col-sm-6" type="text" name="filtro" class="form-control">
											<input class="col-sm-3 ml-2 btn btn-primary" type='submit' name='buscarPrestamo' value='Buscar'>
										</div>
									</form>
								</div>
							</div>

							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>ISBN</th>
										<th>DNI</th>
										<th>INICIO</th>
										<th>FIN</th>
										<th>ESTADO</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
									//Comprobar si hemos pulsado el filtro de búsqueda
									$filtro = "";
									if (isset($_GET['filtro'])) {
										$filtro = filtrado($_GET['filtro']);
									}

									//Paginador
									if (isset($_GET['pagina'])) {
										$pagina = $_GET['pagina'];
									} else {
										$pagina = 1;
									}

									//Mostramos los prestamos desde la BD
									$prestamos = hacerSelectPrestamos($filtro, $pagina);

									//Recorremos los resultados
									foreach ($prestamos as $prestamo) {
									?>
										<tr>
											<td><?php echo $prestamo['ISBN']; ?></td>
											<td><?php echo $prestamo['DNI']; ?></td>
											<td><?php echo $prestamo['fecha_inicio']; ?></td>
											<td><?php echo $prestamo['fecha_fin']; ?></td>
											<td><?php echo $prestamo['estado']; ?></td>
											<td>

												<a href="controlador.php?update_prestamo=<?php echo $prestamo['ISBN']; ?>-<?php echo $prestamo['DNI']; ?>"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
												<a href="controlador.php?delete_prestamo=<?php echo $prestamo['ISBN']; ?>-<?php echo $prestamo['DNI']; ?>"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
											</td>
										</tr>
									<?php
									}

									?>
								</tbody>
								<tfooter>
									<tr style="text-align: center;">
										<td colspan='12'>
											<?php
											//Calculamos el número total de páginas consultando BD
											$np = numPaginasUsuarios($filtro);

											?>
											<a href="index.php?filtro=<?php echo $filtro; ?>&pagina=<?php if ($pagina > 1) echo ($pagina - 1);
																									else echo 1; ?>&accion=paginador_prestamo">
												<< </a> <a href="index.php?filtro=<?php echo $filtro; ?>&pagina=<?php if ($pagina < $np) echo ($pagina + 1);
																												else echo $np; ?>&accion=paginador_prestamo">&nbsp;&nbsp;&nbsp;>>
											</a>
										</td>
									</tr>

								</tfooter>
							</table>


						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Add Libro Modal HTML -->
	<div id="addLibroModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method='POST' action='controlador.php'>
					<div class="modal-header">
						<h4 class="modal-title">Añadir Libro</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>ISBN</label>
							<input type="text" name="isbn" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Título</label>
							<input type="text" name="titulo" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Subtítulo</label>
							<input type="text" name="subtitulo" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Descripción</label>
							<input type="text" name="descripcion" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Autor</label>
							<input type="text" name="autor" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Editorial</label>
							<input type="text" name="editorial" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Categoría</label>
							<select name="categoria" class="form-control">
								<option value="novela">Novela</option>
								<option value="historica">Histórica</option>
								<option value="scifi">Scifi</option>
								<option value="romantica">Romántica</option>
								<option value="ensayo">Ensayo</option>
								<option value="misterio">Misterio</option>
								<option value="viajes">Viajes</option>
							</select>
						</div>
						<div class="form-group">
							<label for="imagen">Imagen de portada</label>
							<!--<input type="file" class="form-control" name="portada" aria-describedby="nombreHelp">-->
							<input type="text" name="portada" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Número de ejemplares totales</label>
							<input type="number" name="total_ejemplares" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Número de ejemplares disponibles</label>
							<input type="number" name="ejemplares_disponibles" class="form-control" required>
						</div>
						<div class="modal-footer">
							<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
							<input type="submit" name='add_libro' class="btn btn-success" value="Añadir">
						</div>

				</form>
			</div>
		</div>
	</div>
	</div>

	<!-- Update Libro Modal HTML -->
	<div id="updateLibroModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method='POST' action='controlador.php'>
					<div class="modal-header">
						<h4 class="modal-title">Modificar Libro</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<input hidden type="text" name="isbn" class="form-control" value="<?php if (isset($_GET['ISBN'])) echo filtrado($_GET['ISBN']);  ?>" required>

						<div class="form-group">
							<label>Título</label>
							<input type="text" name="titulo" class="form-control" value="<?php if (isset($_GET['titulo'])) echo filtrado($_GET['titulo']);  ?>" required>
						</div>
						<div class="form-group">
							<label>Subtítulo</label>
							<input type="text" name="subtitulo" class="form-control" value="<?php if (isset($_GET['subtitulo'])) echo filtrado($_GET['subtitulo']);  ?>" required>
						</div>
						<div class="form-group">
							<label>Descripción</label>
							<input type="text" name="descripcion" class="form-control" value="<?php if (isset($_GET['descripcion'])) echo filtrado($_GET['descripcion']);  ?>" required>
						</div>
						<div class="form-group">
							<label>Autor</label>
							<input type="text" name="autor" class="form-control" value="<?php if (isset($_GET['autor'])) echo filtrado($_GET['autor']);  ?>" required>
						</div>
						<div class="form-group">
							<label>Editorial</label>
							<input type="text" name="editorial" class="form-control" value="<?php if (isset($_GET['editorial'])) echo filtrado($_GET['editorial']);  ?>" required>
						</div>
						<div class="form-group">
							<label>Categoría</label>
							<select name="categoria" class="form-control">
								<?php
								$estado = "";
								if (isset($_GET['categoria']))
									$categoria = filtrado($_GET['categoria']);

								switch ($categoria) {
									case "novela":
										echo '<option value="novela" selected>Novela</option>';
										echo '<option value="historica">Histórica</option>';
										echo '<option value="scifi">Scifi</option>';
										echo '<option value="romantica">Romántica</option>';
										echo '<option value="ensayo">Ensayo</option>';
										echo '<option value="misterio">Misterio</option>';
										echo '<option value="viajes">Viajes</option>';
										break;
									case "historica":
										echo '<option value="novela">Novela</option>';
										echo '<option value="historica" selected>Histórica</option>';
										echo '<option value="scifi">Scifi</option>';
										echo '<option value="romantica">Romántica</option>';
										echo '<option value="ensayo">Ensayo</option>';
										echo '<option value="misterio">Misterio</option>';
										echo '<option value="viajes">Viajes</option>';
										break;
									case "scifi":
										echo '<option value="novela">Novela</option>';
										echo '<option value="historica">Histórica</option>';
										echo '<option value="scifi" selected>Scifi</option>';
										echo '<option value="romantica">Romántica</option>';
										echo '<option value="ensayo">Ensayo</option>';
										echo '<option value="misterio">Misterio</option>';
										echo '<option value="viajes">Viajes</option>';
										break;
									case "romantica":
										echo '<option value="novela">Novela</option>';
										echo '<option value="historica">Histórica</option>';
										echo '<option value="scifi">Scifi</option>';
										echo '<option value="romantica" selected>Romántica</option>';
										echo '<option value="ensayo">Ensayo</option>';
										echo '<option value="misterio">Misterio</option>';
										echo '<option value="viajes">Viajes</option>';
										break;
									case "ensayo":
										echo '<option value="novela">Novela</option>';
										echo '<option value="historica">Histórica</option>';
										echo '<option value="scifi">Scifi</option>';
										echo '<option value="romantica">Romántica</option>';
										echo '<option value="ensayo" selected>Ensayo</option>';
										echo '<option value="misterio">Misterio</option>';
										echo '<option value="viajes">Viajes</option>';
										break;
									case "misterio":
										echo '<option value="novela">Novela</option>';
										echo '<option value="historica">Histórica</option>';
										echo '<option value="scifi">Scifi</option>';
										echo '<option value="romantica">Romántica</option>';
										echo '<option value="ensayo">Ensayo</option>';
										echo '<option value="misterio" selected>Misterio</option>';
										echo '<option value="viajes">Viajes</option>';
										break;
									case "viajes":
										echo '<option value="novela">Novela</option>';
										echo '<option value="historica">Histórica</option>';
										echo '<option value="scifi">Scifi</option>';
										echo '<option value="romantica">Romántica</option>';
										echo '<option value="ensayo">Ensayo</option>';
										echo '<option value="misterio">Misterio</option>';
										echo '<option value="viajes" selected>Viajes</option>';
										break;
									default:
										echo '<option value="novela">Novela</option>';
										echo '<option value="historica">Histórica</option>';
										echo '<option value="scifi">Scifi</option>';
										echo '<option value="romantica">Romántica</option>';
										echo '<option value="ensayo">Ensayo</option>';
										echo '<option value="misterio">Misterio</option>';
										echo '<option value="viajes">Viajes</option>';
										break;
								}
								?>

							</select>
						</div>
						<div class="form-group">
							<label>Imagen de portada</label>
							<input type="text" name="portada" class="form-control" value="<?php if (isset($_GET['portada'])) echo $_GET['portada'];  ?>" required>
						</div>
						<div class="form-group">
							<label>Número de ejemplares totales</label>
							<input type="number" min="1" name="total_ejemplares" class="form-control" value="<?php if (isset($_GET['total_ejemplares'])) echo filtrado($_GET['total_ejemplares']);  ?>" required>
						</div>
						<div class="form-group">
							<label> Número de ejemplares disponibles </label>
							<input type="number" min="0" name="ejemplares_disponibles" class="form-control" value="<?php if (isset($_GET['ejemplares_disponibles'])) echo filtrado($_GET['ejemplares_disponibles']);  ?>" required>
						</div>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" name='update_libro' class="btn btn-success" value="Update">
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
		var accion = window.location.search.indexOf("accion=update_libro");
		var update = window.location.search.indexOf("accion=modificado_libro");
		var buscar = window.location.search.indexOf("buscarLibro=Buscar");
		var add = window.location.search.indexOf("accion=add_libro");
		var eliminar = window.location.search.indexOf("accion=delete_libro");
		var paginador = window.location.search.indexOf("accion=paginador_libro");


		if (accion == 1) {
			$("#updateLibroModal").modal("show");

			//Desactivo usuarios y prestamos		
			document.getElementById("v-pills-usuarios").className = "tab-pane fade show";
			document.getElementById("v-pills-usuarios-tab").className = "nav-link";

			document.getElementById("v-pills-prestamos").className = "tab-pane fade show";
			document.getElementById("v-pills-prestamos-tab").className = "nav-link";

			//Activo libros
			document.getElementById("v-pills-libros").className = "tab-pane fade show active";
			document.getElementById("v-pills-libros-tab").className = "nav-link active";
		}

		if (update > -1 || buscar > -1 || add > -1 || eliminar > -1 || paginador > -1) {
			//Desactivo usuarios y prestamos		
			document.getElementById("v-pills-usuarios").className = "tab-pane fade show";
			document.getElementById("v-pills-usuarios-tab").className = "nav-link";

			document.getElementById("v-pills-prestamos").className = "tab-pane fade show";
			document.getElementById("v-pills-prestamos-tab").className = "nav-link";

			//Activo libros
			document.getElementById("v-pills-libros").className = "tab-pane fade show active";
			document.getElementById("v-pills-libros-tab").className = "nav-link active";
		}
	</script>

	<!--USUARIO-->

	<!-- Add Usuario Modal HTML -->
	<div id="addUsuarioModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method='POST' action='controlador.php'>
					<div class="modal-header">
						<h4 class="modal-title">Añadir Usuario</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>DNI</label>
							<input type="text" name="dni" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Nombre</label>
							<input type="text" name="nombre" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Apellidos</label>
							<input type="text" name="apellidos" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Edad</label>
							<input type="number" min="18" name="edad" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Dirección</label>
							<input type="text" name="direccion" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Población</label>
							<input type="text" name="poblacion" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Teléfono</label>
							<input type="tel" name="telefono" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Email</label>
							<input type="email" name="email" class="form-control" required>
						</div>
						<div class="modal-footer">
							<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
							<input type="submit" name='add_usuario' class="btn btn-success" value="Añadir">
						</div>

				</form>
			</div>
		</div>
	</div>
	</div>

	<!-- Update Usuario Modal HTML -->
	<div id="updateUsuarioModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method='POST' action='controlador.php'>
					<div class="modal-header">
						<h4 class="modal-title">Modificar Usuario</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<input hidden type="text" name="dni" class="form-control" value="<?php if (isset($_GET['DNI'])) echo filtrado($_GET['DNI']);  ?>" required>
						<div class="form-group">
							<label>Nombre</label>
							<input type="text" name="nombre" class="form-control" value="<?php if (isset($_GET['nombre'])) echo filtrado($_GET['nombre']);  ?>" required>
						</div>
						<div class="form-group">
							<label>Apellidos</label>
							<input type="text" name="apellidos" class="form-control" value="<?php if (isset($_GET['apellidos'])) echo filtrado($_GET['apellidos']);  ?>" required>
						</div>
						<div class="form-group">
							<label>Edad</label>
							<input type="number" min="18" name="edad" class="form-control" value="<?php if (isset($_GET['edad'])) echo filtrado($_GET['edad']);  ?>" required>
						</div>
						<div class="form-group">
							<label>Dirección</label>
							<input type="text" name="direccion" class="form-control" value="<?php if (isset($_GET['direccion'])) echo filtrado($_GET['direccion']);  ?>" required>
						</div>
						<div class="form-group">
							<label>Población</label>
							<input type="text" name="poblacion" class="form-control" value="<?php if (isset($_GET['poblacion'])) echo filtrado($_GET['poblacion']);  ?>" required>
						</div>
						<div class="form-group">
							<label>Teléfono</label>
							<input type="tel" name="telefono" class="form-control" value="<?php if (isset($_GET['telefono'])) echo filtrado($_GET['telefono']);  ?>" required>
						</div>
						<div class="form-group">
							<label>Email</label>
							<input type="email" name="email" class="form-control" value="<?php if (isset($_GET['email'])) echo filtrado($_GET['email']);  ?>" required>
						</div>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" name='update_usuario' class="btn btn-success" value="Update">
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
		var accion = window.location.search.indexOf("accion=update_usuario");
		var update = window.location.search.indexOf("accion=modificado_usuario");
		var buscar = window.location.search.indexOf("buscarUsuario=Buscar");
		var add = window.location.search.indexOf("accion=add_usuario");
		var eliminar = window.location.search.indexOf("accion=delete_usuario");
		var paginador = window.location.search.indexOf("accion=paginador_usuario");

		if (accion == 1) {
			$("#updateUsuarioModal").modal("show");

			//Desactivo libros y prestamos
			document.getElementById("v-pills-libros").className = "tab-pane fade show";
			document.getElementById("v-pills-libros-tab").className = "nav-link";

			document.getElementById("v-pills-prestamos").className = "tab-pane fade show";
			document.getElementById("v-pills-prestamos-tab").className = "nav-link";

			//Activo usuarios
			document.getElementById("v-pills-usuarios").className = "tab-pane fade show active";
			document.getElementById("v-pills-usuarios-tab").className = "nav-link active";
		}

		if (update > -1 || buscar > -1 || add > -1 || eliminar > -1 || paginador > -1) {
			//Desactivo libros y prestamos
			document.getElementById("v-pills-libros").className = "tab-pane fade show";
			document.getElementById("v-pills-libros-tab").className = "nav-link";

			document.getElementById("v-pills-prestamos").className = "tab-pane fade show";
			document.getElementById("v-pills-prestamos-tab").className = "nav-link";

			//Activo usuarios
			document.getElementById("v-pills-usuarios").className = "tab-pane fade show active";
			document.getElementById("v-pills-usuarios-tab").className = "nav-link active";
		}
	</script>

	<!--PRESTAMOS-->

	<!-- Add Prestamo Modal HTML -->
	<div id="addPrestamoModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method='POST' action='controlador.php'>
					<div class="modal-header">
						<h4 class="modal-title">Añadir Préstamo</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>ISBN</label>
							<input type="text" name="isbn" class="form-control" required>
						</div>
						<div class="form-group">
							<label>DNI</label>
							<input type="text" name="dni" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Fecha Inicio</label>
							<input type="date" name="fecha_inicio" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Fecha Fin</label>
							<input type="date" name="fecha_fin" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Estado</label>
							<select name="estado" class="form-control">
								<option value="activo">Activo</option>
								<option value="devuelto">Devuelto</option>
								<option value="sobrepasado1Mes">Sobrepasado 1 Mes</option>
								<option value="sobrepasado1Year">Sobrepasado 1 Año</option>
							</select>
						</div>
						<div class="modal-footer">
							<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
							<input type="submit" name='add_prestamo' class="btn btn-success" value="Añadir">
						</div>
				</form>
			</div>
		</div>
	</div>
	</div>

	<!-- Update Prestamo Modal HTML -->
	<div id="updatePrestamoModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method='POST' action='controlador.php'>
					<div class="modal-header">
						<h4 class="modal-title">Modificar Préstamo</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<input type="hidden" name="isbn" value="<?php if (isset($_GET['ISBN'])) echo filtrado($_GET['ISBN']);  ?>">
						<input type="hidden" name="dni" value="<?php if (isset($_GET['DNI'])) echo filtrado($_GET['DNI']);  ?>">
						<div class="form-group">
							<label>Fecha Fin</label>
							<input type="date" name="fecha_fin" class="form-control" value="<?php if (isset($_GET['fecha_fin'])) echo filtrado($_GET['fecha_fin']);  ?>" required>
						</div>
						<div class="form-group">
							<label>Estado</label>
							<select name="estado" class="form-control">
								<?php
								$estado = "";
								if (isset($_GET['estado']))
									$estado = filtrado($_GET['estado']);

								switch ($estado) {
									case "activo":
										echo '<option value="activo" selected>Activo</option>';
										echo '<option value="devuelto">Devuelto</option>';
										echo '<option value="sobrepasado1Mes">Sobrepasado 1 Mes</option>';
										echo '<option value="sobrepasado1Year">Sobrepasado 1 Año</option>';
										break;
									case "devuelto":
										echo '<option value="activo">Activo</option>';
										echo '<option value="devuelto" selected>Devuelto</option>';
										echo '<option value="sobrepasado1Mes">Sobrepasado 1 Mes</option>';
										echo '<option value="sobrepasado1Year">Sobrepasado 1 Año</option>';
										break;
									case "sobrepasado1Mes":
										echo '<option value="activo">Activo</option>';
										echo '<option value="devuelto">Devuelto</option>';
										echo '<option value="sobrepasado1Mes" selected>Sobrepasado 1 Mes</option>';
										echo '<option value="sobrepasado1Year">Sobrepasado 1 Año</option>';
										break;
									case "sobrepasado1Year":
										echo '<option value="activo">Activo</option>';
										echo '<option value="devuelto">Devuelto</option>';
										echo '<option value="sobrepasado1Mes">Sobrepasado 1 Mes</option>';
										echo '<option value="sobrepasado1Year" selected>Sobrepasado 1 Año</option>';
										break;
									default:
										echo '<option value="activo">Activo</option>';
										echo '<option value="devuelto">Devuelto</option>';
										echo '<option value="sobrepasado1Mes">Sobrepasado 1 Mes</option>';
										echo '<option value="sobrepasado1Year">Sobrepasado 1 Año</option>';
										break;
								}
								?>

							</select>
						</div>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" name='update_prestamo' class="btn btn-success" value="Update">
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
		var accion = window.location.search.indexOf("accion=update_prestamo");
		var update = window.location.search.indexOf("accion=modificado_prestamo");
		var buscar = window.location.search.indexOf("buscarPrestamo=Buscar");
		var add = window.location.search.indexOf("accion=add_prestamo");
		var eliminar = window.location.search.indexOf("accion=delete_prestamo");
		var paginador = window.location.search.indexOf("accion=paginador_prestamo");


		if (accion == 1) {
			$("#updatePrestamoModal").modal("show");

			//Desactivo libros y usuarios
			document.getElementById("v-pills-libros").className = "tab-pane fade show";
			document.getElementById("v-pills-libros-tab").className = "nav-link";

			document.getElementById("v-pills-usuarios").className = "tab-pane fade show";
			document.getElementById("v-pills-usuarios-tab").className = "nav-link";
			//Activo prestamos
			document.getElementById("v-pills-prestamos").className = "tab-pane fade show active";
			document.getElementById("v-pills-prestamos-tab").className = "nav-link active";
		}

		if (update > -1 || buscar > -1 || add > -1 || eliminar > -1 || paginador > -1) {
			//Desactivo libros y usuarios
			document.getElementById("v-pills-libros").className = "tab-pane fade show";
			document.getElementById("v-pills-libros-tab").className = "nav-link";

			document.getElementById("v-pills-usuarios").className = "tab-pane fade show";
			document.getElementById("v-pills-usuarios-tab").className = "nav-link";
			//Activo prestamos
			document.getElementById("v-pills-prestamos").className = "tab-pane fade show active";
			document.getElementById("v-pills-prestamos-tab").className = "nav-link active";
		}
	</script>
</body>

</html>