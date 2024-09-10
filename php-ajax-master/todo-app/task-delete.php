<?php
include('database.php');

if (isset($_POST['id'])) {
  $id = $_POST['id'];

  // Preparar la consulta SQL para prevenir inyecciones
  $stmt = $connection->prepare("DELETE FROM task WHERE id = ?");
  
  if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . $connection->error);
  }

  // Enlazar el parámetro (i -> integer)
  $stmt->bind_param("i", $id);

  // Ejecutar la consulta
  $result = $stmt->execute();

  if (!$result) {
    die('Error en la ejecución de la consulta: ' . $stmt->error);
  }

  echo "Tarea eliminada con éxito";

  // Cerrar la declaración
  $stmt->close();
} else {
  echo "No se recibió el ID de la tarea.";
}

// Cerrar la conexión
$connection->close();
?>
