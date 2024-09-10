<?php

include('database.php');

$search = $_POST['search'];

if (!empty($search)) {
  // Preparar la consulta SQL usando LIKE para búsqueda
  $stmt = $connection->prepare("SELECT * FROM task WHERE name LIKE ?");
  
  if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . $connection->error);
  }

  // Agregar el símbolo de porcentaje (%) para la búsqueda con LIKE
  $search_param = $search . '%';

  // Enlazar el parámetro (s -> string)
  $stmt->bind_param("s", $search_param);

  // Ejecutar la consulta
  $stmt->execute();

  // Obtener el resultado
  $result = $stmt->get_result();

  if (!$result) {
    die('Error en la ejecución de la consulta: ' . $stmt->error);
  }

  // Convertir los resultados en un array JSON
  $json = array();
  while ($row = $result->fetch_assoc()) {
    $json[] = array(
      'name' => $row['name'],
      'description' => $row['description'],
      'id' => $row['id']
    );
  }

  // Devolver los datos en formato JSON
  $jsonstring = json_encode($json);
  echo $jsonstring;

  // Cerrar la declaración
  $stmt->close();
}

// Cerrar la conexión
$connection->close();

?>
