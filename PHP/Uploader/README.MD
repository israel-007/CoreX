# PHP Uploader Class

This PHP Uploader Class simplifies file uploads, it allows handling file validation, renaming, and management. It also provides file management methods such as fetching, deleting, renaming, copying, and retrieving file details.

## Features

- Multiple File Uploads – Upload multiple files at once
- File Type Restrictions – Allow specific file formats
- Max File Size Limit – Restrict file sizes (default: 2MB)
- Custom Directory – Set where files should be uploaded
- Auto-Renaming – Optionally rename files with a prefix
- File Management Methods – Delete, copy, rename, move, fetch, and list files
- File Info Retrieval – Get file details like name, size, MIME type, and modification time
- Download Support – Force file downloads

## Usage Examples

1. Upload Files

```php

<?php
require_once 'Uploader.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['files'])) {
    $uploaded = Uploader::directory('uploads/')
        ->uploadFiles($_FILES['files'])
        ->filetypes(["jpeg", "pdf", "mp4"])  // Allowed file types
        ->maxsize(2)  // Max file size (2MB)
        ->rename(true, 'myPrefix')  // Enable renaming with prefix
        ->upload();

    if (!empty($uploaded)) {
        echo "Files uploaded successfully!";
        print_r($uploaded);
    } else {
        echo "Errors:";
        print_r(Uploader::directory('uploads/')->getErrors());
    }
}
?>

```

2. Fetch Files in a Folder

```php

<?php

$files = Uploader::directory('uploads/')->fetch();
print_r($files);

?>

```

3. Fetch Specific File Types & Info

```php

<?php

$files = Uploader::directory('uploads/')
    ->filetypes(['images', 'jpeg', 'mp4', 'gif', 'video'])
    ->info(['name', 'size', 'mime'])
    ->fetch();

print_r($files);

?>

```

4. Delete a File

```php

<?php

if (Uploader::directory('uploads/')->delete('oldfile.jpg')) {
    echo "File deleted!";
} else {
    echo "File not found!";
}

?>

```

5. Copy a File

```php

<?php

// Copy file within the same folder
Uploader::directory('uploads/')->copy('example.jpg');

// Copy file to another folder
Uploader::directory('uploads/')->copy('example.jpg', 'backup/');

?>

```

6. Rename a File

```php

<?php

Uploader::directory('uploads/')->renameFile('oldname.jpg', 'newname.jpg');

?>

```

7. Move a File to Another Folder

```php

<?php

Uploader::directory('uploads/')->move('file.jpg', 'archived/');

?>

```

8. Check if a File Exists

```php

<?php

if (Uploader::directory('uploads/')->exists('file.jpg')) {
    echo "File exists!";
} else {
    echo "File does not exist.";
}

?>

```

9. Get Specific File Info

```php

<?php

$fileInfo = Uploader::directory('uploads/')->fileinfo('example.jpg');
print_r($fileInfo);

?>

```

## Why Use This PHP Uploader Class?

1. Simple & Intuitive API
- Uses fluent method chaining, making it easy to configure and use.
- Reduces boilerplate code while keeping it highly customizable.

2. Secure & Reliable File Uploads
- Restricts file types to prevent unauthorized uploads (e.g., only images, videos, PDFs).
- Limits file size to prevent large uploads that may slow down the server.
- Automatic renaming to prevent file overwrites and ensure unique file storage.

3. Full File Management System
- Supports uploading, renaming, deleting, moving, and copying files effortlessly.
- Can fetch and filter files based on type or retrieve specific file details.

4. Performance & Efficiency
- Handles multiple files at once, reducing the number of requests.
- Uses lazy loading for file fetching, ensuring smooth performance even with large directories.
- Automatically creates missing directories, preventing errors.

5. Developer-Friendly
- Includes error handling and logging for debugging failed uploads.
- Provides detailed file information, such as size, MIME type, and last modified time.
- Easy integration with any PHP project—no dependencies required.

6. Ideal for Various Applications
- File Upload Systems – Allow users to upload profile pictures, documents, or media.
- Media Management – Organize images, videos, or audio files dynamically.
- Data Processing – Manage and manipulate uploaded files with ease.


## Upcoming Improvements / Contributions

- Download a File
- List Folders Inside a Directory
- Count Files in a Directory
- Clear a Folder (Delete All Files)
- Get Last Modified Time of a File
- Create a ZIP Archive of Files

