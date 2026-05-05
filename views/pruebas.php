<?php
require_once 'configuration/database.php';
require_once 'models/Producto.php';

$db= new Database();
$conexion = $db->getConnection();

$producto=new Producto($conexion);
$mitabla = $producto->consultar();


foreach ($mitabla as $fila)
    echo $fila['id_producto'].',';
?>