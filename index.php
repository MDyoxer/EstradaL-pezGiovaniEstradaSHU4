<?php
require_once 'config/database.php';

echo "<h1>¡Hola Mundo con Base de Datos!</h1>";

$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "<p style='color: green;'>✅ Conexión a PostgreSQL exitosa</p>";
    
    try {
        $query = "CREATE TABLE IF NOT EXISTS estudiantes (
            id SERIAL PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            matricula VARCHAR(20) NOT NULL,
            creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $db->exec($query);
        echo "<p>✅ Tabla 'estudiantes' creada/verificada</p>";
        
        $insert = "INSERT INTO estudiantes (nombre, matricula) 
                   VALUES ('Juan Pérez', 'A01234567')
                   ON CONFLICT DO NOTHING";
        $db->exec($insert);
        
        $stmt = $db->query("SELECT * FROM estudiantes");
        $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h2>Estudiantes en la base de datos:</h2>";
        echo "<ul>";
        foreach ($estudiantes as $estudiante) {
            echo "<li>{$estudiante['nombre']} - {$estudiante['matricula']}</li>";
        }
        echo "</ul>";
        
    } catch(PDOException $exception) {
        echo "<p style='color: red;'>Error: " . $exception->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ No se pudo conectar a la base de datos</p>";
}
?>