<?php
  include('database.php');

  if (isset($_POST['name']) && isset($_POST['description'])) {
    $task_name = $_POST['name'];
    $task_description = $_POST['description'];

    // Preparar la consulta SQL para prevenir inyecciones
    $stmt = $connection->prepare("INSERT INTO task (name, description) VALUES (?, ?)");

    if ($stmt === false) {
      die('Error en la preparación de la consulta: ' . $connection->error);
    }

    // Enlazar los parámetros
    $stmt->bind_param("ss", $task_name, $task_description);  // "ss" significa dos strings (s -> string)

    // Ejecutar la consulta
    $result = $stmt->execute();

    if (!$result) {
      die('Error en la ejecución de la consulta: ' . $stmt->error);
    }

    echo "Tarea añadida con éxito";  

    // Cerrar la declaración
    $stmt->close();
  } else {
    echo "Faltan datos obligatorios";
  }

  // Cerrar la conexión
  $connection->close();
?>
