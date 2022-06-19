<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Productos</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Langar&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" ></script>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
      <div class="barra">
          <div class="contenedor contenido-header">
              <a href="main.php" class="contenido-logo">
                  <img src="img/logo-inicio-prueba.png" alt="Logotipo">
                  <h4>KAKAWASI SHOP</h4>
              </a>

              <nav class="navegacion">
                  <a href="main.php">Inicio</a>
                  <a href="verproductos.php">Productos</a>
                  <a href="#">Nosotros</a></nav>

              <!-- Barra superior -->
                  <?php
                    session_start();
                    if (isset($_SESSION['cliente_nombre'])) { ?>

                    <a><?php echo "Bienvenido ".$_SESSION['cliente_nombre']; ?> </a>
                    <a class="nav-link" data-bs-toggle="modal" data-bs-target="#emergentecarrito" style="color: #ffbe33; cursor:pointer;"><i class="fas fa-shopping-cart"></i></a>

                    <!-- CARRITO EMERGENTE -->

                    <div class="modal fade" id="emergentecarrito" tabindex="-1"  aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Mi carrito</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">

                    <!-- CONTENIDO DEL CARRITO EMERGENTE -->





                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <a type="button" class="btn btn-primary" href="borrarcarro.php">Vaciar carrito</a>
                          </div>
                        </div>
                      </div>
                    </div>

                    <nav class="navegacionamarillo"><a href="logout.php">Cerrar sesión</a></nav>
                    <?php } else { ?>
                          <nav class="navegacionamarillo"><a href="login.php">Iniciar sesión</a></nav>
                    <?php }?>

          </div>
      </div>
  </header>
  <div class="contenedorp ">
        <h2 class="fw-300 centrar-texto">Nuestros Productos</h2>
        </div>
        <div class="contenedorp contenedor-categorias">
<?php
require 'conexion.php';
    //Consulta para encontrar la cantidad total de registros con estado = 1
    /*$ConsultaTotalRegistroProductos =
      'SELECT COUNT(*) as total_registros
      FROM producto
      WHERE prod_estado =1';
    $ResultadoConsultaTotalRegistroProductos = mysqli_query($conex, $ConsultaTotalRegistroProductos);
    $data_registrosproductos = mysqli_fetch_array($ResultadoConsultaTotalRegistroProductos);
    $TotalRegistrosProductos = $data_registrosproductos['total_registros'];*/
    //echo $TotalRegistrosProductos;
    //Productos por pagina
    /*$por_pagina = 4;
    if (empty($_GET['pagina'])) {
        $pagina = 1;
    } else {
        $pagina = $_GET['pagina'];
    }
    $desde = ($pagina-1)* $por_pagina;
    $total_paginas = ceil($TotalRegistrosProductos/$por_pagina);
    $hasta =$desde+$por_pagina;*/

    // CONSULTA PARA EXTRAER LOS DATOS DE LA BD
    $ConsultaProductosDisponibles=
    'SELECT DISTINCT producto.prod_nombre as nombre, producto.prod_precio as precio, producto.prod_descripcion as descripcion, producto.prod_estado as estado , producto.prod_imagen as imagen,
     producto.fabricante_prod as fabricante /*, local.local_ubicacion as ubicacion, local.local_ciudad as ciudad*/
    FROM producto
    /*INNER JOIN detalle_local on producto.idproducto = detalle_local.producto_id
    INNER JOIN local on local.idlocal = detalle_local.local_id*/
    ORDER BY prod_estado DESC ';
    $ResultadoConsultaProductosDisponibles = mysqli_query($conex, $ConsultaProductosDisponibles);

    // variable con la cantidad de filas del array, da un número
    $CantidadProductosListados = mysqli_num_rows($ResultadoConsultaProductosDisponibles);
    //echo $CantidadProductosListados;
    //Bucle para tener el array para los productos en estado "1"
    $a = 0;
    //nota: con mysqli_fetch_array salian el doble de columnas por dato, si eran 7, aparecian 14 arrays 7 de numeros y 7 de letras, solo queremos el de letras, por ende usarmos mysqli_fetch_assoc




    //ARRAY PARA ALMACENAR LOS DATOS DE LAS FILAS EN UN ARRAY DATAPRODUCTOS
    while ($datofila = mysqli_fetch_assoc($ResultadoConsultaProductosDisponibles)) {
        $dataproductos[$a]=$datofila;
        $a++;
    }

    //BUCLE PARA MOSTRAR LOS PRODUCTOS
    $orden=0;
    while ($orden < $CantidadProductosListados) {
        ?>
              <div class="productos">
              <?php echo '<img src="data:image/jpeg;base64,'.base64_encode($dataproductos[$orden]['imagen']).'"/>'; ?>

              <div class="contenido-categorias">
                  <h3><?php echo $dataproductos[$orden]['nombre']; ?></h3>
                  <p><?php echo $dataproductos[$orden]['descripcion']; ?></p>

                  <!-- valida si el producto no tiene stock -->
                  <p class="precio"><?php
                  if ($dataproductos[$orden]['estado']>0) {
                      echo '$'.$dataproductos[$orden]['precio'].'.00'; ?></p>
                    <?php
                  } else {?>
                      <p class="nodisponible"> <?php echo 'No Disponible'; ?></p><?php
                  } ?>

                  <!-- valida inicio de sesion para hacer la compra -->
                  <?php
                  if ($dataproductos[$orden]['estado']>0) {?>
                    <div class="seccion-comprar">
                    <button name="btnCompra" type="submit" class="boton boton-carrito d-block">Comprar</button>
                    <button name="btnCarrito" type="submit" class="boton boton-carrito d-block">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </button>
                    </p>
                    </div>
                    <?php
                  }

        //BOTON DE CARRITO (EN LA DESCRIPCION DEL PRODUCTO)

        /*if (isset($_SESSION['cliente_nombre'])) {
            if (isset($_POST['btnCarrito'])) {
                header("location: ../main.php");
            }
        } else {
            header("location: ../login.php");
        } */?>


               </div>
            </div>
<?php
        $orden++;
    }
 ?>
</div>
  </body>
</html>
