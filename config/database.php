<?php
class Database {
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        // Obtener DATABASE_URL de las variables de entorno
        $database_url = getenv('DATABASE_URL');
        
        if (!$database_url) {
            echo "<!-- DATABASE_URL no configurada -->";
            return null;
        }

        try {
            // Parsear la URL de la base de datos
            $db_params = parse_url($database_url);
            
            // Extraer componentes
            $host = $db_params['host'];
            $port = $db_params['port'] ?? '5432';
            $dbname = ltrim($db_params['path'], '/');
            $username = $db_params['user'];
            $password = $db_params['pass'];
            
            // Crear DSN para PostgreSQL
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            
            $this->conn = new PDO($dsn, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            echo "<!-- Conexión exitosa usando DATABASE_URL -->";
            
        } catch(PDOException $exception) {
            echo "<!-- Error de conexión: " . $exception->getMessage() . " -->";
        }
        
        return $this->conn;
    }
}
?>
