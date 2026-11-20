<?php

require_once __DIR__ . '/env.php';

$databaseDriver = strtolower(agencia_env('AGENCIA_DB_DRIVER', 'mysql'));

if ($databaseDriver === 'pgsql' || $databaseDriver === 'postgres' || $databaseDriver === 'postgresql') {
    require_once __DIR__ . '/conexion_pdo.php';

    class AgenciaPdoResult
    {
        private $rows;
        private $index = 0;
        public $num_rows = 0;

        public function __construct($rows)
        {
            $this->rows = array_map([$this, 'normalizeRow'], $rows);
            $this->num_rows = count($rows);
        }

        private function normalizeRow($row)
        {
            foreach ($row as $key => $value) {
                if (is_resource($value)) {
                    $row[$key] = stream_get_contents($value);
                }
            }

            if (array_key_exists('fotou', $row) && !array_key_exists('fotoU', $row)) {
                $row['fotoU'] = $row['fotou'];
            }

            return $row;
        }

        public function fetch_assoc()
        {
            if ($this->index >= $this->num_rows) {
                return null;
            }

            return $this->rows[$this->index++];
        }
    }

    class AgenciaPdoStatement
    {
        private $stmt;
        private $params = [];

        public function __construct(PDOStatement $stmt)
        {
            $this->stmt = $stmt;
        }

        public function bind_param($types, &...$params)
        {
            $this->params = $params;
            return true;
        }

        public function execute()
        {
            return $this->stmt->execute($this->params);
        }

        public function get_result()
        {
            return new AgenciaPdoResult($this->stmt->fetchAll());
        }

        public function close()
        {
            $this->stmt->closeCursor();
            return true;
        }
    }

    class AgenciaPdoConnection
    {
        private $pdo;
        public $connect_error = null;

        public function __construct(PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        public function query($sql)
        {
            return new AgenciaPdoResult($this->pdo->query($sql)->fetchAll());
        }

        public function prepare($sql)
        {
            return new AgenciaPdoStatement($this->pdo->prepare($sql));
        }

        public function real_escape_string($value)
        {
            return str_replace(["\\", "\0", "\n", "\r", "'", '"'], ["\\\\", "\\0", "\\n", "\\r", "''", '\"'], $value);
        }

        public function close()
        {
            return true;
        }
    }

    if (!function_exists('mysqli_query')) {
        function mysqli_query($conn, $sql)
        {
            return $conn->query($sql);
        }
    }

    if (!function_exists('mysqli_num_rows')) {
        function mysqli_num_rows($result)
        {
            return $result ? $result->num_rows : 0;
        }
    }

    if (!function_exists('mysqli_fetch_assoc')) {
        function mysqli_fetch_assoc($result)
        {
            return $result ? $result->fetch_assoc() : null;
        }
    }

    if (!function_exists('mysqli_prepare')) {
        function mysqli_prepare($conn, $sql)
        {
            return $conn->prepare($sql);
        }
    }

    if (!function_exists('mysqli_stmt_bind_param')) {
        function mysqli_stmt_bind_param($stmt, $types, &...$params)
        {
            return $stmt->bind_param($types, ...$params);
        }
    }

    if (!function_exists('mysqli_stmt_execute')) {
        function mysqli_stmt_execute($stmt)
        {
            return $stmt->execute();
        }
    }

    if (!function_exists('mysqli_stmt_get_result')) {
        function mysqli_stmt_get_result($stmt)
        {
            return $stmt->get_result();
        }
    }

    if (!function_exists('mysqli_stmt_close')) {
        function mysqli_stmt_close($stmt)
        {
            return $stmt->close();
        }
    }

    if (!function_exists('mysqli_connect_errno')) {
        function mysqli_connect_errno()
        {
            return 0;
        }
    }

    if (!function_exists('mysqli_connect_error')) {
        function mysqli_connect_error()
        {
            return '';
        }
    }

    if (!function_exists('mysqli_real_escape_string')) {
        function mysqli_real_escape_string($conn, $value)
        {
            return $conn->real_escape_string($value);
        }
    }

    if (!function_exists('mysqli_close')) {
        function mysqli_close($conn)
        {
            return $conn->close();
        }
    }

    $conn = new AgenciaPdoConnection($pdo);
    return;
}

$servername = agencia_env('AGENCIA_DB_HOST', 'localhost');
$username = agencia_env('AGENCIA_DB_USER', 'root');
$password = agencia_env('AGENCIA_DB_PASSWORD', '');
$databasename = agencia_env('AGENCIA_DB_NAME', 'agencia');
$port = (int) agencia_env('AGENCIA_DB_PORT', '3306');

$conn = mysqli_connect($servername, $username, $password, $databasename, $port);

if (!$conn) {
    die('Error de conexion: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
?>
