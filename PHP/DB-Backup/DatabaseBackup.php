<?php

class DatabaseBackup
{
    private $pdo;
    private $backupType = 'database'; // 'database' or 'table'
    private $tableName = null;
    private $format = 'sql'; // 'sql' or 'csv'
    private $savePath = __DIR__ . '/backups/';
    private $fileName;
    private $download = false;

    public function __construct($host, $dbname, $username, $password)
    {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    public function table($tableName)
    {
        $this->backupType = 'table';
        $this->tableName = $tableName;
        return $this;
    }

    public function format($format)
    {
        if (!in_array($format, ['sql', 'csv'])) {
            throw new InvalidArgumentException("Invalid format. Allowed: 'sql', 'csv'");
        }
        $this->format = $format;
        return $this;
    }

    public function dir($path)
    {
        $this->savePath = rtrim($path, '/') . '/';
        return $this;
    }

    public function download()
    {
        $this->download = true;
        return $this;
    }

    public function backup()
    {
        $date = date('Y-m-d_H-i-s');
        $this->fileName = ($this->backupType === 'table' ? $this->tableName : 'database') . "_backup_{$date}.{$this->format}";

        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0777, true);
        }

        $filePath = $this->savePath . $this->fileName;

        if ($this->format === 'sql') {
            $this->backupSQL($filePath);
        } else {
            $this->backupCSV($filePath);
        }

        if ($this->download) {
            $this->forceDownload($filePath);
        }

        return $filePath;
    }

    private function backupSQL($filePath)
    {
        $output = "-- Backup Generated on " . date('Y-m-d H:i:s') . "\n\n";

        if ($this->backupType === 'database') {
            $tables = $this->pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($tables as $table) {
                $output .= $this->getTableSQL($table);
            }
        } else {
            $output .= $this->getTableSQL($this->tableName);
        }

        file_put_contents($filePath, $output);
    }

    private function getTableSQL($table)
    {
        $output = "-- Table: $table\n";

        // Get CREATE TABLE statement
        $createTableQuery = $this->pdo->query("SHOW CREATE TABLE `$table`")->fetch();
        $output .= $createTableQuery['Create Table'] . ";\n\n";

        // Get INSERT statements
        $rows = $this->pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $values = array_map([$this->pdo, 'quote'], array_values($row));
            $output .= "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
        }

        $output .= "\n\n";
        return $output;
    }

    private function backupCSV($filePath)
    {
        $fp = fopen($filePath, 'w');

        if ($this->backupType === 'database') {
            $tables = $this->pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($tables as $table) {
                $this->writeTableToCSV($table, $fp);
            }
        } else {
            $this->writeTableToCSV($this->tableName, $fp);
        }

        fclose($fp);
    }

    private function writeTableToCSV($table, $fp)
    {
        fwrite($fp, "-- Table: $table\n");
        $rows = $this->pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        if (empty($rows))
            return;

        // Write headers
        fputcsv($fp, array_keys($rows[0]));

        // Write data
        foreach ($rows as $row) {
            fputcsv($fp, $row);
        }

        fwrite($fp, "\n\n");
    }

    private function forceDownload($filePath)
    {
        if (!file_exists($filePath)) {
            die("File not found.");
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
        exit;
    }

    public function getBackupByDate($date)
    {
        $pattern = $this->savePath . "*_backup_{$date}_*.{$this->format}";
        $files = glob($pattern);
        return $files;
    }
}
