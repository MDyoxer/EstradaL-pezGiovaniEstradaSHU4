<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

echo "<h1>¡Hola Mundo con Base de Datos!</h1>";

if ($db) {
    echo "<p style='color: green;'>✅ Conexión a PostgreSQL exitosa</p>";
    
    try {
        // Crear tabla de ejemplo (solo si no existe)
        $query = "CREATE TABLE IF NOT EXISTS estudiantes (
            id SERIAL PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            matricula VARCHAR(20) NOT NULL UNIQUE,  // ⬅️ EVITA DUPLICADOS
            creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $db->exec($query);
        echo "<p>✅ Tabla 'estudiantes' creada/verificada</p>";
        
        // Insertar datos de ejemplo SOLO si no existen
        $insert = "INSERT INTO estudiantes (nombre, matricula) 
                   VALUES ('Juan Pérez', 'A01234567')
                   ON CONFLICT (matricula) DO NOTHING";  // ⬅️ EVITA DUPLICADOS
        $db->exec($insert);
        
        // Mostrar estudiantes
        $stmt = $db->query("SELECT * FROM estudiantes ORDER BY id");
        $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h2>Estudiantes en la base de datos:</h2>";
        echo "<ul>";
        foreach ($estudiantes as $estudiante) {
            echo "<li>{$estudiante['nombre']} - {$estudiante['matricula']} (ID: {$estudiante['id']})</li>";
        }
        echo "</ul>";
        
        echo "<p><strong>Total de estudiantes:</strong> " . count($estudiantes) . "</p>";
        
    } catch(PDOException $exception) {
        echo "<p style='color: red;'>Error: " . $exception->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ No se pudo conectar a la base de datos</p>";
}

echo "<hr>";
echo "<h3>🚀 ¡Aplicación desplegada exitosamente!</h3>";
echo "<p><strong>URL:</strong> https://estradal-pezgiovaniestradashu4.onrender.com</p>";
echo "<p><strong>Base de datos:</strong> PostgreSQL en Render</p>";
echo "<p><em>Sistema SHU4 - " . date('Y-m-d H:i:s') . "</em></p>";
?>