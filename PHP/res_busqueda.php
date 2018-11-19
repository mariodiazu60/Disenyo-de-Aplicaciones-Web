﻿<?php
    include 'pre_cabecera.php';
    $title = "Resultado búsqueda";
    require_once("../Plantilla/cabecera.inc");
    require_once("../Plantilla/inicio.inc");
?>
		<nav>
			<?php
                if (isset($_COOKIE['usuario_recordado'])) {
                    require_once("../Plantilla/nav_si.inc");
                } elseif (isset($_SESSION['usuario_sesion'])) {
                    require_once("../Plantilla/nav_si.inc");
                } else {
                    require_once("../Plantilla/nav_no.inc");
                }
            ?>
			<form name="busqueda" class="buscador" action="res_busqueda.php" method="post">
				<input type="search" name="buscar" placeholder="Buscar">
                <input class="puntero_mano" type="submit" name="Enviar">
			</form>
		</nav>

		<h2 class="titulo_filtros_busq">Resultado de la b&uacute;squeda:</h2>
		<section class="filtros_busq">
            <?php
                if (isset($_POST["buscar"])) { $busc = $_POST["buscar"]; }
                if (isset($_POST["tit"])) { $tit = $_POST["tit"]; }
                if (isset($_POST["date1"])) { $fecha1 = $_POST["date1"]; }
                if (isset($_POST["date2"])) { $fecha2 = $_POST["date2"]; }
                if (isset($_POST["pais"])) { $pais = $_POST["pais"]; }

                if (!empty($busc)) {
                    echo "<p><b>Buscado: ".$busc."</b></p>";
                }
                if (!empty($tit)) {
                    echo "<p><b>Título: ".$tit."</b></p>";
                }
                if (!empty($fecha1)) {
                    echo "<p><b>Desde: ".$fecha1."</b></p>";
                }
                if (!empty($fecha2)) {
                    echo "<p><b>Hasta: ".$fecha2."</b></p>";
                }
                if (!empty($pais)) {
                    if ($pais == 1) {
                        $pais = "España";
                    } elseif ($pais == 2) {
                        $pais = "Francia";
                    } elseif ($pais == 3) {
                        $pais = "Alemania";
                    }
                    echo "<p><b>País: ".$pais."</b></p>";
                }
            ?>
            <div>
                <?php
                    $enlace = @mysqli_connect("localhost", "root", "", "pibd");

                    if (!$enlace) {
                        echo '<p>Error al conectar con la base de datos: ' . mysqli_connect_error(); 
                        echo '</p>'; 
                        exit;
                    }

                    $sentencia = "SELECT Titulo, Descripcion, Fecha, NomPais, Fichero, Alternativo from fotos, paises WHERE (fotos.Titulo='$tit' AND fotos.Fecha BETWEEN '$fecha1' AND '$fecha2' AND fotos.Pais='$pais') AND fotos.Pais=paises.IdPais";

                    if(!($resultado = @mysqli_query($enlace, $sentencia))) { 
                       echo "<p>Error al ejecutar la sentencia <b>$sentencia</b>: " . mysqli_error($enlace); 
                       echo '</p>';
                       exit; 
                    }

                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        echo "<article>";
                        echo "<p>".$fila['Titulo']."</p>";
                        echo "<figure>";
                        echo "<a title='".$fila['Descripcion']."' href='detalle.php?id=1'><img src='../Imagenes/".$fila['Fichero'].".jpg' alt='".$fila['Alternativo']."' width=100% height=100%></a>";
                        echo "</figure>";
                        echo "<footer>";
                        echo "<p>".$fila['Fecha']." | ".$fila['NomPais']."</p>";
                        echo "</footer>";
                        echo "</article>";
                    }

                    mysqli_free_result($resultado);
                    mysqli_close($enlace);
                ?>
            </div>
		</section>
<?php
    require_once("../Plantilla/pie.inc");
?>
