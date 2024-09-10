<?php
include('database.php');

if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['description'])) {
  $task_name = $_POST['name']; 
  $task_description = $_POST['description'];
  $id = $_POST['id'];

  // Preparar la consulta SQL para prevenir inyecciones
  $stmt = $connection->prepare("UPDATE task SET name = ?, description = ? WHERE id = ?");
  
  if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . $connection->error);
  }

  // Enlazar los parámetros (ss -> strings, i -> entero)
  $stmt->bind_param("ssi", $task_name, $task_description, $id);

  // Ejecutar la consulta
  $result = $stmt->execute();

  if (!$result) {
    die('Error en la ejecución de la consulta: ' . $stmt->error);
  }

  echo "Tarea actualizada con éxito";

  // Cerrar la declaración
  $stmt->close();
} else {
  echo "Faltan datos obligatorios.";
}

// Cerrar la conexión
$connection->close();
?>
