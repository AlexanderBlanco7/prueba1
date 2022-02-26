<?php

require_once 'conex.php';


if( isset($_POST['nombre_r']) ){
    $nombre_r = $_POST['nombre_r'];
    $sql = "INSERT INTO rubros (nombre) VALUES ('$nombre_r')";

    $resultado = $conn->query($sql);

    if($resultado === TRUE){
        echo 'Se insertó correctamente el registro';
    }
    else{
        echo 'Ha ocurrido un error '.$conn->error; 
    }

}

if( isset($_POST['id_r']) ){
    $id_r = $_POST['id_r'];
    $sql = "DELETE FROM rubros WHERE id='$id_r'";

    $resultado = $conn->query($sql);

    if($resultado === TRUE){
        echo 'Se borró correctamente el registro';
    }
    else{
        echo 'Ha ocurrido un error '.$conn->error; 
    }

}

if( isset($_POST['id_actualizar_r']) && isset($_POST['nombre_actualizar_r']) ){
    $id_r = $_POST['id_actualizar_r'];
    $nombre_r = $_POST['nombre_actualizar_r'];
    //La consulta permite actualizar registros segun la condicion que se agrega en el where
    $sql = "UPDATE rubros SET nombre='$nombre_r' WHERE id='$id_r'";

    $resultado = $conn->query($sql);

    if($resultado === TRUE){
        echo 'Se actualizó correctamente el registro';
    }
    else{
        echo 'Ha ocurrido un error '.$conn->error; 
    }

}

$sql2 = "SELECT * FROM rubros";

$rubros = $conn->query($sql2);

?>

<form action="Rubros.php" method="POST">
    <input type="text" name="nombre_r" maxlength="250">
    <button type="submit">Enviar</button>
    <button type="reset">Resetear campos</button>
</form>


<?= 'Cantidad de rubros actualmente:'.$rubros->num_rows; ?>

<table>
    <thead>
        <th>Id</th>
        <th>Nombre</th>
        <th>Editar</th>
        <th>Eliminar</th>
    </thead>
    <tbody>
        <?php foreach($rubros->fetch_all() as $value){ ?>
        <tr>
            <td><?php echo $value[0]; ?></td>
            <td><?= $value[1]; ?></td>
            <td>
            <form action="Rubros.php" method="POST">                
                <input type="hidden" name="id_actualizar_r" value="<?=$value[0]; ?>">
                <input type="text" name="nombre_actualizar_r" maxlength="250" value="<?= $value[1]; ?>">
                <button type="submit" onclick="return confirm('Está seguro que desea editar el registro?')">Editar</button>
            </form>
            </td>
            <td>
            <form action="Rubros.php" method="POST">
                <input type="hidden" name="id_r" value="<?=$value[0]; ?>">
                <button type="submit" onclick="return confirm('Está seguro que desea eliminar el registro?')">Eliminar</button>
            </form>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>