<?php

declare(strict_types=1);
define('ROOT_PATH', __DIR__ . '/../'); // Assuming your script is in 'public' and .env is in the parent directory

require ROOT_PATH . 'vendor/autoload.php';

use Dotenv\Dotenv;

// Define the ROOT_PATH where your .env file should be located
// Adjust this based on your project structure (e.g., if index.php is in 'public/')

// --- START DEBUGGING ---
$envFilePath = ROOT_PATH . '.env';

echo "--- DOTENV DEBUG START ---\n";
echo "1. Checking if the .env file exists at: " . $envFilePath . "\n";

if (!file_exists($envFilePath)) {
    echo "CRITICAL ERROR: .env file NOT FOUND.\n";
    echo "Check your ROOT_PATH definition in the script.\n";
    die(1);
}

echo "2. File found. Checking read permissions.\n";

if (!is_readable($envFilePath)) {
    echo "CRITICAL ERROR: .env file found, but PHP lacks READ permissions.\n";
    die(1);
}

echo "3. File exists and is readable. Attempting to load...\n";
// --- END DEBUGGING ---


try {
    $dotenv = Dotenv::createImmutable(ROOT_PATH);
    $dotenv->load();
    
    // Now check if variables were loaded (e.g., check for APP_ENV)
    $appEnv = getenv('APP_ENV'); 
    
    echo "--- LOAD COMPLETE ---\n";
    echo "APP_ENV via getenv(): " . ($appEnv ?: "Variable not set.") . "\n";
    var_dump($_ENV);

} catch (\Exception $e) {
    echo "DOTENV EXCEPTION: " . $e->getMessage() . "\n";
    die(1);
}

// ... rest of your application code