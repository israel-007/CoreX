<?php

class Uploader
{
    private static string $uploadDir = "uploads/";
    private static array $allowedTypes = [];
    private static int $maxSize = 2 * 1024 * 1024; // Default 2MB
    private static bool $renameFiles = false;
    private static ?string $prefix = null;
    private static array $errors = [];
    private static array $infoFields = [];
    private static array $files = [];

    // Set directory
    public static function directory(string $dir): self
    {
        self::$uploadDir = rtrim($dir, '/') . '/';

        if (!is_dir(self::$uploadDir)) {
            mkdir(self::$uploadDir, 0777, true);
        }

        return new self();
    }

    // Set allowed file types
    public function filetypes(array $types): self
    {
        self::$allowedTypes = array_map('strtolower', $types);
        return $this;
    }

    // Set max file size (in MB)
    public function maxsize(int $size): self
    {
        self::$maxSize = $size * 1024 * 1024;
        return $this;
    }

    // Enable/Disable renaming
    public function rename(bool $rename = false, string $prefix = null): self
    {
        self::$renameFiles = $rename;
        self::$prefix = $prefix;
        return $this;
    }

    // Store files array for upload
    public function uploadFiles(array $files): self
    {
        self::$files = $files;
        return $this;
    }

    // Execute the upload process
    public function upload(): array
    {
        $uploadedFiles = [];

        foreach (self::$files['name'] as $index => $name) {
            $fileTmp = self::$files['tmp_name'][$index];
            $fileSize = self::$files['size'][$index];
            $fileType = mime_content_type($fileTmp);
            $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));

            if (!$fileTmp || self::$files['error'][$index] !== UPLOAD_ERR_OK) {
                self::$errors[] = "Error uploading file: $name";
                continue;
            }

            if (!empty(self::$allowedTypes) && !in_array($fileExt, self::$allowedTypes)) {
                self::$errors[] = "Invalid file type for $name";
                continue;
            }

            if ($fileSize > self::$maxSize) {
                self::$errors[] = "File size exceeds limit for $name";
                continue;
            }

            // Rename file if enabled
            $newName = self::$renameFiles
                ? (self::$prefix ? self::$prefix . "_" : "") . uniqid() . ".$fileExt"
                : $name;

            $destination = self::$uploadDir . $newName;

            if (move_uploaded_file($fileTmp, $destination)) {
                $uploadedFiles[] = [
                    "original_name" => $name,
                    "new_name" => $newName,
                    "size" => $fileSize,
                    "type" => $fileType,
                    "path" => $destination
                ];
            } else {
                self::$errors[] = "Failed to move file: $name";
            }
        }

        return $uploadedFiles;
    }

    // Fetch files from directory
    public function fetch(): array
    {
        $files = array_diff(scandir(self::$uploadDir), ['.', '..']);
        $fileData = [];

        foreach ($files as $file) {
            $filePath = self::$uploadDir . $file;
            $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $mimeType = mime_content_type($filePath);

            if (!empty(self::$allowedTypes) && !in_array($fileExt, self::$allowedTypes)) {
                continue;
            }

            $fileInfo = [
                'name' => $file,
                'size' => filesize($filePath),
                'mime' => $mimeType,
                'path' => $filePath,
            ];

            if (!empty(self::$infoFields)) {
                $fileInfo = array_intersect_key($fileInfo, array_flip(self::$infoFields));
            }

            $fileData[] = $fileInfo;
        }

        return $fileData;
    }

    // Select specific file information
    public function info(array $fields): self
    {
        self::$infoFields = $fields;
        return $this;
    }

    // Delete a file
    public function delete(string $filename): bool
    {
        $filePath = self::$uploadDir . $filename;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    // Copy a file to another folder or duplicate it
    public function copy(string $filename, string $destinationFolder = null): bool
    {
        $sourcePath = self::$uploadDir . $filename;
        $destinationFolder = $destinationFolder ? rtrim($destinationFolder, '/') . '/' : self::$uploadDir;

        if (!is_dir($destinationFolder)) {
            mkdir($destinationFolder, 0777, true);
        }

        $destinationPath = $destinationFolder . "copy_" . $filename;

        return file_exists($sourcePath) ? copy($sourcePath, $destinationPath) : false;
    }

    // Move a file to another folder
    public function move(string $filename, string $destinationFolder): bool
    {
        $sourcePath = self::$uploadDir . $filename;
        $destinationFolder = rtrim($destinationFolder, '/') . '/';

        if (!is_dir($destinationFolder)) {
            mkdir($destinationFolder, 0777, true);
        }

        $destinationPath = $destinationFolder . $filename;

        return file_exists($sourcePath) ? rename($sourcePath, $destinationPath) : false;
    }

    // Rename a file
    public function renameFile(string $oldName, string $newName): bool
    {
        $oldPath = self::$uploadDir . $oldName;
        $newPath = self::$uploadDir . $newName;

        return file_exists($oldPath) ? rename($oldPath, $newPath) : false;
    }

    // Get file info
    public function fileinfo(string $filename): array
    {
        $filePath = self::$uploadDir . $filename;

        if (!file_exists($filePath)) {
            return ["error" => "File not found"];
        }

        return [
            "name" => $filename,
            "size" => filesize($filePath),
            "mime" => mime_content_type($filePath),
            "path" => $filePath
        ];
    }

    // Check if a file exists
    public function exists(string $filename): bool
    {
        return file_exists(self::$uploadDir . $filename);
    }


    // Get errors
    public function getErrors(): array
    {
        return self::$errors;
    }
}
