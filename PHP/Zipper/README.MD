# PHP Zipper Class

A lightweight PHP class to create, manage, and download ZIP files dynamically. This class allows you to add files or entire folders, generate ZIP archives, retrieve file details, and manage storage efficiently.

## Features
* Create ZIP files dynamically
* Add single or multiple files
* Add entire folders recursively
* Download ZIP files with a custom filename
* Remove ZIP files after use
* Get detailed ZIP file information
* Secure and efficient file handling
* Streams large ZIP files to prevent memory exhaustion

## Usage Example

### Basic Example

```php

<?php
    require_once 'Zipper.php';

    // Create an instance of Zipper
    $zipper = new Zipper('my_archive');

    // Add files
    $zipper->add('folder/file1.jpg');
    $zipper->add('folder/file2.mp4');

    // Create the ZIP file
    echo $zipper->zip();

    // Download the ZIP file
    $zipper->download();
?>

```

## Add an Entire Folder

```php 

<?php
    $zipper = new Zipper('my_folder_backup');

    // Add all files inside a folder (recursively)
    $zipper->addFolder('documents');

    // Create ZIP
    echo $zipper->zip();
?>

```

## Download with a Custom Filename

```php

<?php
    $zipper = new Zipper('project_backup');

    $zipper->add('project/code.php');
    $zipper->add('project/config.json');

    // Create ZIP
    echo $zipper->zip();

    // Download with a custom filename
    $zipper->download('backup.zip');
?>


```

## Get ZIP File Information

```php

<?php
    echo Zipper::info('my_archive.zip');
?>


```

### Example Output:

```bash

    {
    "success": true,
    "zip_file": "my_archive.zip",
    "total_files": 5,
    "zip_size": "10.2 MB",
    "files": [
        {"name": "file1.jpg", "size": "200 KB", "compressed_size": "150 KB"},
        {"name": "file2.mp4", "size": "10 MB", "compressed_size": "8.9 MB"}
    ]
}

```

## Delete a ZIP File

```php

<?php
    $zipper = new Zipper('old_backup');
    echo $zipper->remove();
?>

```

## Why Use This PHP Zipper Class?

This PHP-based ZIP manager provides an efficient, lightweight, and flexible way to handle ZIP file operations without requiring external software.

1. Secure & Reliable
- Uses ZipArchive, PHP’s built-in library, ensuring compatibility and stability.
- Prevents file duplicates and corrupt ZIP issues by handling file locks properly.
- Supports large file streaming, avoiding PHP memory exhaustion.
2. Saves Time & Storage
- On-the-fly compression: Reduces file sizes for optimized storage and transfer speeds.
- Auto-delete feature: Optionally removes ZIPs after download to save disk space.
3. Works Everywhere (No Browser Dependencies)
- Unlike browser-based solutions, this works entirely on the server.
- Does not rely on JavaScript, cookies, or local storage.
- Supports Incognito Mode and cross-device access.
4. Optimized for Large Files
- Uses chunk-based streaming for downloading ZIP files.
- Prevents timeout issues when handling large ZIP archives.
5. Great for Web Applications
- Backup and restore file systems
- Download multiple files as a single ZIP
- Store and archive user uploads
- Export database reports as ZIP archives




