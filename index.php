<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Web SHU4 - Universidad</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 1.2em;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .card h2 {
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-success {
            color: #10b981;
            background: #ecfdf5;
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid #10b981;
            margin: 10px 0;
        }

        .status-info {
            color: #3b82f6;
            background: #eff6ff;
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
            margin: 10px 0;
        }

        .status-error {
            color: #ef4444;
            background: #fef2f2;
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid #ef4444;
            margin: 10px 0;
        }

        .students-list {
            list-style: none;
        }

        .students-list li {
            padding: 15px;
            margin: 8px 0;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .student-info {
            font-weight: 500;
            color: #333;
        }

        .student-id {
            color: #6b7280;
            font-size: 0.9em;
        }

        .load-balancing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .instance-card {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #e2e8f0;
        }

        .instance-id {
            font-family: 'Courier New', monospace;
            background: #333;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9em;
        }

        .footer {
            text-align: center;
            color: white;
            margin-top: 40px;
            opacity: 0.8;
        }

        .tech-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 5px 12px;
            border-radius: 20px;
            margin: 5px;
            font-size: 0.9em;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2em;
            }
            
            .load-balancing-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Sistema Web Universitario</h1>
            <p>SHU4 - Arquitectura de Aplicaciones Web</p>
        </div>

        <?php
        require_once 'config/database.php';
        $database = new Database();
        $db = $database->getConnection();

        $hostname = gethostname();
        $server_ip = $_SERVER['SERVER_ADDR'] ?? 'No disponible';
        $timestamp = microtime(true);
        $instance_id = substr(md5($hostname), 0, 8);
        ?>

        <div class="card">
            <h2>📊 Estado del Sistema</h2>
            
            <?php if ($db): ?>
            <div class="status-success">
                ✅ <strong>Conexión a PostgreSQL exitosa</strong> - Base de datos operativa
            </div>
            <?php else: ?>
            <div class="status-error">
                ❌ Error en la conexión a la base de datos
            </div>
            <?php endif; ?>

            <div class="status-info">
                🔄 <strong>Servidor de aplicaciones funcionando</strong> - PHP 8.1 + Apache
            </div>
        </div>

        <div class="card">
            <h2>🔄 Balanceo de Cargas</h2>
            <p>Sistema distribuido con múltiples instancias para alta disponibilidad</p>
            
            <div class="load-balancing-grid">
                <div class="instance-card">
                    <strong>Instancia Actual</strong>
                    <div class="instance-id"><?php echo $instance_id; ?></div>
                    <small>Host: <?php echo substr($hostname, 0, 15); ?>...</small>
                </div>
                <div class="instance-card">
                    <strong>IP del Servidor</strong>
                    <div style="font-family: monospace; margin: 5px 0;"><?php echo $server_ip; ?></div>
                </div>
                <div class="instance-card">
                    <strong>Tiempo de Respuesta</strong>
                    <div style="font-size: 1.2em; margin: 5px 0;">
                        <?php echo number_format((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000, 2); ?>ms
                    </div>
                </div>
            </div>

            <div style="margin-top: 15px; padding: 12px; background: #f0f9ff; border-radius: 8px;">
                <small>
                    💡 <strong>Prueba el balanceo:</strong> Recarga la página varias veces. 
                    En un entorno de producción con múltiples instancias, podrías ver diferentes IDs de instancia.
                </small>
            </div>
        </div>

        <div class="card">
            <h2>🗃️ Base de Datos</h2>
            
            <?php
            if ($db) {
                try {
                    $db->exec("CREATE TABLE IF NOT EXISTS estudiantes (
                        id SERIAL PRIMARY KEY,
                        nombre VARCHAR(100) NOT NULL,
                        matricula VARCHAR(20) NOT NULL,
                        creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    )");

                    $db->exec("ALTER TABLE estudiantes ADD CONSTRAINT IF NOT EXISTS matricula_unique UNIQUE (matricula)");

                    $check_stmt = $db->query("SELECT COUNT(*) as count FROM estudiantes WHERE matricula = 'A01234567'");
                    $exists = $check_stmt->fetch(PDO::FETCH_ASSOC)['count'] == 0;

                    if ($exists) {
                        $insert_stmt = $db->prepare("INSERT INTO estudiantes (nombre, matricula) VALUES (?, ?)");
                        $insert_stmt->execute(['Ana García Rodríguez', 'A01234567']);
                        echo '<div class="status-success">✅ Estudiante de ejemplo insertado</div>';
                    }

                    $stmt = $db->query("SELECT * FROM estudiantes ORDER BY creado_en DESC");
                    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    echo '<div class="status-success">';
                    echo '✅ <strong>Tabla de estudiantes verificada</strong> - ' . count($estudiantes) . ' registros encontrados';
                    echo '</div>';

                    if (count($estudiantes) > 0) {
                        echo '<h3 style="margin: 20px 0 10px 0;">Estudiantes Registrados:</h3>';
                        echo '<ul class="students-list">';
                        foreach ($estudiantes as $estudiante) {
                            echo '<li>';
                            echo '<span class="student-info">' . $estudiante['nombre'] . ' - ' . $estudiante['matricula'] . '</span>';
                            echo '<span class="student-id">ID: ' . $estudiante['id'] . '</span>';
                            echo '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<div class="status-info">No hay estudiantes registrados aún.</div>';
                    }

                } catch(PDOException $exception) {
                    echo '<div class="status-error">Error en la base de datos: ' . $exception->getMessage() . '</div>';
                    
                    try {
                        $stmt = $db->query("SELECT * FROM estudiantes ORDER BY creado_en DESC");
                        $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (count($estudiantes) > 0) {
                            echo '<h3 style="margin: 20px 0 10px 0;">Estudiantes Registrados:</h3>';
                            echo '<ul class="students-list">';
                            foreach ($estudiantes as $estudiante) {
                                echo '<li>';
                                echo '<span class="student-info">' . $estudiante['nombre'] . ' - ' . $estudiante['matricula'] . '</span>';
                                echo '<span class="student-id">ID: ' . $estudiante['id'] . '</span>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        }
                    } catch (Exception $e) {
                        echo '<div class="status-error">No se pudieron cargar los estudiantes: ' . $e->getMessage() . '</div>';
                    }
                }
            }
            ?>
        </div>

        <div class="footer">
            <p>🚀 <strong>Sistema desplegado en Render</strong> - Arquitectura cloud escalable</p>
            <div style="margin: 15px 0;">
                <span class="tech-badge">PHP 8.1</span>
                <span class="tech-badge">PostgreSQL</span>
                <span class="tech-badge">Docker</span>
                <span class="tech-badge">Apache</span>
                <span class="tech-badge">HTTPS</span>
                <span class="tech-badge">Load Balancing</span>
            </div>
            <p>URL: https://estradal-pezgiovaniestradashu4.onrender.com</p>
            <p style="margin-top: 10px; font-size: 0.9em;">
                📅 <?php echo date('d/m/Y H:i:s'); ?> 
                | 🆔 Instancia: <?php echo $instance_id; ?>
            </p>
        </div>
    </div>
</body>
</html>