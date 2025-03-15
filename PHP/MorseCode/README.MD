# PHP MorseCode Class

This PHP class converts text to custom Morse code encryption and back. This implementation allows for dynamic Morse mapping based on a user-defined encryption key, ensuring unique encoding per project and preventing unauthorized decryption.

## Features

* Encrypts text into Morse code using a dynamic mapping
* Decrypts Morse code back to text using the same encryption key
* Unique encryption per project (Morse encoding is different based on key)
* Prevents duplicate mappings (ensures each character gets a unique Morse code)
* More values than keys, ensuring better randomness and uniqueness
* Validates for duplicates in keys/values and stops execution if any are found

## Usage Examples

### Encrypt a String into Morse Code

```php

<?php
require_once 'MorseCode.php';

$encryptionKey = "SuperSecretKey2024"; // Unique per project
$morse = new MorseCode($encryptionKey);

$text = "Hello World";
$encrypted = $morse->encrypt($text);

echo $encrypted;
?>

```

### Example Output:

```bash 

--..- ..- --.. .-..- ..-- / .---. --.- ..--- .-.. -.-.

```

### Decrypt Morse Code Back to Text

```php

<?php
$decrypted = $morse->decrypt($encrypted);
echo $decrypted;
?>

```

### Using a Different Encryption Key

```php

<?php 

$anotherKey = "DifferentProjectKey"; 
$morse2 = new MorseCode($anotherKey);

echo $morse2->encrypt("Hello World");

?>

```

> [!NOTE]
> ⚠ IMPORTANT:
>  
> The encryption output will be completely different if the key changes.
> This ensures that only users with the correct key can decrypt messages!

## Why Use This PHP MorseCode Class?

1. Security & Customization
- Encryption is tied to a user-defined key (prevents unauthorized decryption)
- Each project gets a unique Morse mapping, making brute-force decoding nearly impossible
- Uses more Morse values than characters, adding an extra layer of security

2. Ideal for Various Applications
- Secure Communications (Hidden Morse encryption for messages)
- Custom Encoding Systems (Unique to each application)
- Game Development & Challenges (Create puzzles with hidden Morse codes)
- Data Obfuscation (Prevent easy reverse-engineering of encoded data)





