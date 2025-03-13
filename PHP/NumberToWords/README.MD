# PHP NumberToWords Class

A lightweight PHP class for converting numbers into words, Roman numerals, ordinal numbers, and compact formats. It also supports decimal numbers and handles large values efficiently.

## Features
* Convert numbers to words (supports large numbers)
* Convert decimal numbers to words
* Convert numbers to Roman numerals
* Convert numbers to ordinal format (1st, 2nd, 3rd)
* Convert numbers to compact format (1K, 2.5M, 3B)
* Handles negative numbers
* Works with both integers and floats

## Usage Examples

### Convert Numbers to Words

```php

<?php
    require_once 'NumberToWords.php';

    echo NumberToWords::convert(25847584257); 
?>

```
### Convert Decimal Numbers to Words

```php

<?php
    echo NumberToWords::convert(75.25);
?>

```

### Convert to Roman Numerals

```php

<?php
    echo NumberToWords::toRoman(2024); 
    // Output: "MMXXIV"
?>

```

### Convert to Ordinal Numbers
* Full Word Format

```php

<?php
    echo NumberToWords::toOrdinalWords(21);
    // Output: "Twenty-First"
?>

```

* Numeric Suffix Format (1st, 2nd, 3rd)

```php

<?php
    echo NumberToWords::toOrdinalSuffix(89);
    // Output: "89th"
?>

```

# Convert to Compact Format

```php

<?php
    echo NumberToWords::convertCompact(1500000); 
    // Output: "1.5M"
?>

```

## Why Use This PHP NumberToWords Class?

1. Accurate & Reliable
- Supports both integers and decimal numbers
- Handles large numbers efficiently
- Converts negative numbers correctly

2. Flexible Output
- Works with Roman numerals, ordinal formats, and compact representations
- Supports both full words and numeric formats

3. Ideal for Various Applications
- Financial & Invoice Systems (Convert amounts to words)
- Ranking & Sorting (Ordinal numbers: "1st, 2nd, 3rd")
- Data Presentation (Compact numbers: "1.2M, 5B")
- History & Education (Roman numerals: "XIV, MMXXIV")















