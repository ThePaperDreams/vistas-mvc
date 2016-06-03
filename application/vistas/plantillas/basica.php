<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?= $this->titulo; ?></title>
	<link rel="stylesheet" href="<?= URL ?>/public/librerias/bootstrap-3/css/bootstrap.css">
	<link rel="stylesheet" href="<?= URL ?>/public/librerias/awesomeFont/css/font_awesome.css">
	<link rel="stylesheet" href="<?= URL ?>/public/librerias/select2/css/select2.css">

	<script src="<?= URL ?>/public/librerias/jquery/jquery.js"></script>
	<script src="<?= URL ?>/public/librerias/bootstrap-3/js/boostrap.js"></script>
	<script src="<?= URL ?>/public/librerias/select2/js/select2.js"></script>
</head>
<body>
	
	<?php 
	# opciones para el menú
	$opciones = [
		(Object)['t' => 'Inicio', 'url' => URL ],
		(Object)['t' => 'Opción1', 'url' => URL . 'home/index'],
	];

	 ?>

	<div class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse" aria-expanded="false">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?= URL ?>">Prax-sys</a>
			</div>
			<div id="bs-example-navbar-collapse" class="collapse navbar-collapse">
				<ul class="nav navbar-nav ">
					<?php foreach($opciones AS $opc): ?>
					<li>
						<a href="<?= $opc->url ?>"><?= $opc->t ?></a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>

	<div class="container">			
		<?= $this->contenido ?>
	</div>
</body>
</html>