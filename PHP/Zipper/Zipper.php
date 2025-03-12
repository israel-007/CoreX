<?php

class Zipper
{
    private string $zipFileName;
    private string $zipFilePath;
    private array $files = [];
    private string $savePath;

    public function __construct(string $zipFileName, string $savePath = null)
    {
        $this->zipFileName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $zipFileName) . '.zip';
        $this->savePath = $savePath ?? __DIR__.'/zips'; // Default to current directory
        $this->zipFilePath = rtrim($this->savePath, '/') . '/' . $this->zipFileName;
    }

    // Add a file if it exists and is not a duplicate
    public function add(string $filePath): void
    {
        if (file_exists($filePath) && !in_array($filePath, $this->files)) {
            $this->files[] = $filePath;
        } else {
            throw new Exception("File not found or already added: $filePath");
        }
    }

    // Add all files from a folder (Recursively)
    public function addFolder(string $folderPath): void
    {
        if (!is_dir($folderPath)) {
            throw new Exception("Invalid folder: $folderPath");
        }

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath));

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $this->add($file->getPathname());
            }
        }
    }

    // Create the ZIP file
    public function zip(): string
    {
        if (empty($this->files)) {
            return json_encode(["error" => "No files added for zipping"]);
        }

        $zip = new ZipArchive();
        if ($zip->open($this->zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return json_encode(["error" => "Failed to create ZIP file"]);
        }

        foreach ($this->files as $file) {
            $zip->addFile($file, basename($file));
        }

        $zip->close();

        // Ensure the file system is updated before returning
        clearstatcache();
        
        // 100ms delay to ensure file is written completely
        usleep(100000);

        return json_encode([
            "success" => true,
            "zip_file" => $this->zipFileName,
            "file_count" => count($this->files),
            "zip_size" => self::formatSize(filesize($this->zipFilePath))
        ]);
    }

    // Stream ZIP file
    public function download(string $downloadName = null, bool $autoDelete = false): void
    {
        if (!file_exists($this->zipFilePath)) {
            die(json_encode(["error" => "ZIP file not found"]));
        }

        // Ensure no previous output interferes
        if (ob_get_length()) {
            ob_end_clean();
        }

        $downloadName = $downloadName ? preg_replace('/[^a-zA-Z0-9_.-]/', '_', $downloadName) : $this->zipFileName;

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $downloadName . '"');
        header('Content-Length: ' . filesize($this->zipFilePath));
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: 0');
        header('Pragma: public');

        // Stream file in chunks
        $handle = fopen($this->zipFilePath, "rb");
        while (!feof($handle)) {
            echo fread($handle, 1024 * 8); // Read in 8KB chunks
            flush();
        }
        fclose($handle);

        if ($autoDelete) {
            unlink($this->zipFilePath);
        }
        exit;
    }

    // Remove the ZIP file
    public function remove(): string
    {
        if (file_exists($this->zipFilePath)) {
            unlink($this->zipFilePath);
            return json_encode(["success" => true, "message" => "ZIP file deleted"]);
        } else {
            return json_encode(["error" => "ZIP file not found"]);
        }
    }

    // Get info about a ZIP file
    public static function info(string $zipFile, string $savePath = null): string
    {
        $zipFilePath = rtrim($savePath ?? __DIR__.'/zips', '/') . '/' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $zipFile);

        if (!file_exists($zipFilePath)) {
            return json_encode(["error" => "ZIP file not found"]);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath) !== true) {
            return json_encode(["error" => "Failed to open ZIP file"]);
        }

        $fileList = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $fileList[] = [
                "name" => $stat['name'],
                "size" => self::formatSize($stat['size']),
                "compressed_size" => self::formatSize($stat['comp_size'])
            ];
        }

        $zip->close();

        return json_encode([
            "success" => true,
            "zip_file" => basename($zipFilePath),
            "total_files" => count($fileList),
            "zip_size" => self::formatSize(filesize($zipFilePath)),
            "files" => $fileList
        ]);
    }

    // Convert to KB, MB, GB, TB
    private static function formatSize(int $bytes): string
    {
        $units = ['bytes', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return number_format($bytes, 2) . " " . $units[$i];
    }
}