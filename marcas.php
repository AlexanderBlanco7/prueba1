<?php
$mensaje = null;
$error = null;
require_once 'conex.php';
if( isset($_POST['metodo']) ){
    switch($_POST['metodo']){
        case 'crear':
            if( isset($_POST['nombre']) ){
                $nombre = $_POST['nombre'];
                $sql = "INSERT INTO marcas (nombre) VALUES ('$nombre')";
            
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
                $sql = "DELETE FROM marcas WHERE id='$id'";
            
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
            if( isset($_POST['id']) && isset($_POST['nombre']) ){
                $id = $_POST['id'];
                $nombre = $_POST['nombre'];
                //La consulta permite actualizar registros segun la condicion que se agrega en el where
                $sql = "UPDATE marcas SET nombre='$nombre' WHERE id='$id'";
            
                $resultado = $conn->query($sql);
            
                if($resultado === TRUE){
                    $mensaje = 'Se actualizó correctamente el registro';
                }
                else{
                    $error = 'Se ha producido un error';
                }
            
            }
        break;
    }
}

$sql2 = "SELECT * FROM marcas";
$marcas = $conn->query($sql2);
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
    <div class="card-header">Agregar una marca</div>
    <div class="card-body">
        <form action="/index.php?opcion=marcas" method="POST">
            <input type="hidden" name="metodo" value="crear">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" name="nombre" maxlength="250">
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

<div class="card mt-3">
  <div class="card-header">
    Listado de marcas
  </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>
                        <?php foreach($marcas->fetch_all() as $value){ ?>
                        <tr>
                            <td><?= $value[0]; ?></td>
                            <td><?= $value[1]; ?></td>
                            <td>
                            <div class="btn-group" role="group" aria-label="Acciones">
                                <button type="button" class="btn btn-warning" data-id="<?= $value[0]; ?>" data-nombre="<?= $value[1]; ?>" data-toggle="modal" data-target="#modalEditar">Editar</button>
                                <form action="index.php?opcion=marcas" method="POST">
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


<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar marca</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form id="form_editar" action="/index.php?opcion=marcas" method="POST">
            <div class="modal-body">
                <input type="hidden" name="metodo" value="editar">
                <input type="hidden" name="id" id="id_editar">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" maxlength="250" id="nombre_editar" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
  </div>
</div>

<script>
    $('#modalEditar').on('show.bs.modal', function (e) {
        // e.relatedTarget Es el boton que abrió el modal
        let id = $(e.relatedTarget).data('id') //En el boton hay una etiqueta que se llama data-id
        let nombre = $(e.relatedTarget).data('nombre') //En el boton hay una etiqueta que se llama data-nombre
        $('#id_editar').val(id) //document.getElementById('#id_editar').value
        $('#nombre_editar').val(nombre)
    })
</script>