<?php
$mensaje = null;
$error = null;
require_once 'conex.php';
if( isset($_POST['metodo']) ){
    switch($_POST['metodo']){
        case 'crear':
            if( 
                isset($_POST['nombre']) &&
                isset($_POST['cantidad']) &&
                isset($_POST['precio']) &&
                isset($_POST['marca']) &&
                isset($_POST['rubro'])
            ){
                $nombre = $_POST['nombre'];
                $cantidad = $_POST['cantidad'];
                $precio = $_POST['precio'];
                $marca = $_POST['marca'];
                $rubro = $_POST['rubro'];
                $sql = "INSERT INTO productos (nombre, cantidad, precio, id_marca, id_rubro) VALUES ('$nombre', '$cantidad', '$precio', '$marca', '$rubro')";
            
                $resultado = $conn->query($sql);
            
                if($resultado === TRUE){
                    $mensaje = 'Se ha creado exitosamente el registro';
                }
                else{
                    $error = 'Se ha producido un error';
                }
            
            }
        break;

        case 'eliminar':
            if( isset($_POST['id']) ){
                $id = $_POST['id'];
                $sql = "DELETE FROM productos WHERE id='$id'";
            
                $resultado = $conn->query($sql);
            
                if($resultado === TRUE){
                    $mensaje = 'Se ha eliminado exitosamente el registro';
                }
                else{
                    $error = 'Se ha producido un error';
                }
            
            }
        break;

        case 'editar':
            if( 
                isset($_POST['id']) &&
                isset($_POST['nombre']) &&
                isset($_POST['cantidad']) &&
                isset($_POST['precio']) &&
                isset($_POST['marca']) &&
                isset($_POST['rubro']) ){

                $id = $_POST['id'];
                $nombre = $_POST['nombre'];
                $cantidad = $_POST['cantidad'];
                $precio = $_POST['precio'];
                $marca = $_POST['marca'];
                $rubro = $_POST['rubro'];

                //La consulta permite actualizar registros segun la condicion que se agrega en el where
                $sql = "UPDATE productos SET nombre='$nombre', cantidad='$cantidad', precio='$precio', id_marca='$marca', id_rubro='$rubro' WHERE id='$id'";
            
                $resultado = $conn->query($sql);
            
                if($resultado === TRUE){
                    $mensaje = 'Se actualizó correctamente el registro';
                }
                else{
                    $error = 'Se ha producido un error';
                    var_dump($conn->error);
                }
            
            }
        break;
    }
}

//Join sin utilizar la palabra join
/* $consulta_sin_join = "SELECT productos.id,productos.nombre,productos.id_marca,productos.id_rubro,productos.cantidad,productos.precio,marcas.nombre as nombre_marca, rubros.nombre as nombre_rubro
FROM productos,marcas,rubros WHERE productos.id_marca = marcas.id and productos.id_rubro = rubros.id"; */

//otra forma de hacer join (Mas recomendada)
$consulta = "SELECT productos.id,productos.nombre,productos.id_marca,productos.id_rubro,productos.cantidad,productos.precio,marcas.nombre as nombre_marca, rubros.nombre as nombre_rubro
FROM productos
JOIN marcas ON marcas.id = productos.id_marca
JOIN rubros ON rubros.id = productos.id_rubro";
$productos = $conn->query($consulta);

$consulta = "SELECT * FROM marcas";
$marcas = $conn->query($consulta);

$consulta = "SELECT * FROM rubros";
$rubros = $conn->query($consulta);
?>

<?php if($mensaje){ ?>
<div class="row">
    <div class="col-12">
        <div class="alert alert-success" role="alert">
        <?= $mensaje; ?>
        </div>
    </div>
</div>
<?php } ?>

<?php if($error){ ?>
<div class="row">
    <div class="col-12">
        <div class="alert alert-danger" role="alert">
        <?= $error; ?>
        </div>
    </div>
</div>
<?php } ?>

<div class="card">
    <div class="card-header">Agregar un producto</div>
    <div class="card-body">
        <form action="/index.php?opcion=productos" method="POST">
            <input type="hidden" name="metodo" value="crear">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" name="nombre" maxlength="250" required>
                </div>
                <div class="col-md-4">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" class="form-control" name="cantidad" max="9999" min="0" step="1" required>
                </div>
                <div class="col-md-4">
                    <label for="cantidad">Precio</label>
                    <input type="number" class="form-control" name="precio" max="99999999" min="0.01" step="0.01" required>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <label for="marca">Marca</label>
                    <select class="form-control" name="marca" id="marca" required>
                        <!-- fetch_all devuelve todos los registros, y cada indice del array corresponde a una columna según como es traido por la consulta -->
                        <?php foreach($marcas->fetch_all() as $value){ ?>
                            <option value="<?= $value[0]; ?>"><?= $value[1]; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="rubro">Rubro</label>
                    <select class="form-control" name="rubro" id="rubro" required>
                        <!-- fetch_assoc devuelve registro a registro, y cada indice del array tiene el mismo nombre que la columna de la consulta (LA OPCION MAS ZARPADA)-->
                        <?php while($value = $rubros->fetch_assoc()){ ?>
                            <option value="<?= $value['id']; ?>"><?= $value['nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-2 mt-2">
                    <button class="btn btn-block btn-primary">Continuar</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- BLOQUE DE EDICION -->
    <?php if(isset($_GET['editar'])){ 
        $consulta = "SELECT * FROM productos";
        $producto = $conn->query($consulta);
        $producto = $producto->fetch_assoc();
        if($producto){
    ?>
        
        <div class="card mt-3">
        <div class="card-header">Editar un producto</div>
        <div class="card-body">
            <form action="/index.php?opcion=productos" method="POST">
                <input type="hidden" name="metodo" value="editar">
                <input type="hidden" name="id" value="<?=$producto['id']; ?>">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" value="<?= $producto['nombre']?>" maxlength="250" required>
                    </div>
                    <div class="col-md-4">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" class="form-control" name="cantidad" value="<?= $producto['cantidad']?>" max="9999" min="0" step="1" required>
                    </div>
                    <div class="col-md-4">
                        <label for="cantidad">Precio</label>
                        <input type="number" class="form-control" name="precio" value="<?= $producto['precio']?>" max="99999999" min="0.01" step="0.01" required>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <label for="marca">Marca</label>
                        <select class="form-control" name="marca" id="marca" required>
                            <!-- fetch_all devuelve todos los registros, y cada indice del array corresponde a una columna según como es traido por la consulta -->
                            
                            <?php 
                                $marcas->data_seek(0); //Reinicia la posicion del array para poder recorrerlo nuevamente
                                foreach($marcas->fetch_all() as $value){ ?>
                                <option value="<?= $value[0]; ?>" <?= $value[0] == $producto['id_marca'] ? 'selected' : null; ?> ><?= $value[1]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="rubro">Rubro</label>
                        <select class="form-control" name="rubro" id="rubro" required>
                            <!-- fetch_assoc devuelve registro a registro, y cada indice del array tiene el mismo nombre que la columna de la consulta (LA OPCION MAS ZARPADA)-->
                            <?php 
                                $rubros->data_seek(0); //Reinicia la posicion del array para poder recorrerlo nuevamente
                                while($value = $rubros->fetch_assoc()){ ?>
                                <option value="<?= $value['id']; ?>" <?= $value['id'] == $producto['id_rubro'] ? 'selected' : null; ?> ><?= $value['nombre']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row justify-content-center mt-2">
                    <div class="col-md-2">
                        <button class="btn btn-block btn-primary">Continuar</button>
                    </div>
                    <div class="col-md-2">
                        <a href="index.php?opcion=productos" class="btn btn-block btn-danger">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php }} ?>
<!-- FIN BLOQUE DE EDICION -->

<div class="card mt-3">
  <div class="card-header">
    Listado de productos
  </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>id_marca (esto no se muestra)</th>
                        <th>id_rubro (esto no se muestra)</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Marca</th>
                        <th>Rubro</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>
                        <?php foreach($productos->fetch_all() as $value){ ?>
                        <tr>
                            <td><?= $value[0]; ?></td>
                            <td><?= $value[1]; ?></td>
                            <td><?= $value[2]; ?></td>
                            <td><?= $value[3]; ?></td>
                            <td><?= $value[4]; ?></td>
                            <td><?= $value[5]; ?></td>
                            <td><?= $value[6]; ?></td>
                            <td><?= $value[7]; ?></td>
                            <td>
                            <div class="btn-group" role="group" aria-label="Acciones">
                                <a href="index.php?opcion=productos&editar=<?=$value[0]; ?>" class="btn btn-warning">Editar</a>
                                <form action="index.php?opcion=productos">
                                    <input type="hidden" name="metodo" value="eliminar">
                                    <input type="hidden" name="id" value="<?=$value[0]; ?>">
                                    <button class="btn btn-danger" onclick="return confirm('Está seguro que desea eliminar el registro?')">Eliminar</button>
                                </form>
                            </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>