# PHP Database Backup Class

This PHP Database Backup Class allows you to create backups of your database with flexibility. You can back up a specific table or the entire database in SQL or CSV format, save the backup to a file, or download it directly. The class also provides an easy way to retrieve backups based on the date they were created.

## Features

- Full Database Backup – Backup the entire database
- Table-Specific Backup – Backup a single table
- SQL or CSV Format – Choose between SQL and CSV formats
- Save to File – Store backups in a custom directory
- Instant Download – Option to download the backup immediately
- Auto-Renaming – Backups include timestamps in filenames
- Retrieve Backups by Date – Easily find backups from a specific date

## Usage Examples

1. Backup the Whole Database (SQL)

```php

require_once 'DatabaseBackup.php';

$backup = new DatabaseBackup('localhost', 'my_database', 'root', '');
$backup->format('sql')->saveTo(__DIR__ . '/backups')->backup();

```

2. Backup a Specific Table (CSV)

```php

$backup->table('users')->format('csv')->backup();

```

3. Download the Backup Immediately

```php

$backup->table('orders')->format('sql')->download()->backup();

```

4. Retrieve a Backup by Date

```php

$files = $backup->getBackupByDate('2025-03-15');
print_r($files);

```

## Why Use This PHP Database Backup Class?

1. Simple & Intuitive API
- Uses fluent method chaining, making it easy to configure.
- Reduces boilerplate code while keeping it highly customizable.

2. Secure & Reliable Backups
- Ensures accurate backups by generating valid SQL statements.
- Supports CSV format for easy viewing and data migration.
- Prevents overwriting by automatically renaming backup files with timestamps.

3. Full Backup Management
- Backs up an entire database or a specific table.
- Allows retrieving past backups based on the date.
- Provides automatic directory creation for backups.

4. Performance & Efficiency
- Uses optimized SQL queries for quick data extraction.
- Handles large databases efficiently.

5. Developer-Friendly
- Includes error handling for debugging failed backups.
- Supports SQL & CSV formats for flexible database restoration.
- Easy integration with any PHP project—no dependencies required.




















