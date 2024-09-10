<?php

include('database.php');

if (isset($_POST['id'])) {
  $id = $_POST['id'];

  // Preparar la consulta para evitar inyecciones SQL
  $stmt = $connection->prepare("SELECT * FROM task WHERE id = ?");
  
  if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . $connection->error);
  }

  // Enlazar el parámetro (i -> integer)
  $stmt->bind_param("i", $id);

  // Ejecutar la consulta
  $stmt->execute();

  // Obtener los resultados
  $result = $stmt->get_result();

  if ($result === false) {
    die('Error en la ejecución de la consulta: ' . $stmt->error);
  }

  // Convertir el resultado en un array JSON
  $json = array();
  while ($row = $result->fetch_assoc()) {
    $json[] = array(
      'name' => $row['name'],
      'description' => $row['description'],
      'id' => $row['id']
    );
  }

  // Devolver solo el primer resultado como un JSON
  $jsonstring = json_encode($json[0]);
  echo $jsonstring;

  // Cerrar la declaración
  $stmt->close();
}

// Cerrar la conexión
$connection->close();

?>
