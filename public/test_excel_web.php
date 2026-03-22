<?php

// Web-accessible test to check if Excel package is available
echo "<h2>Excel Package Web Test</h2>";

require_once '../vendor/autoload.php';

echo "<h3>Testing direct package access...</h3>";

try {
    // Test if we can access the Excel classes directly
    if (class_exists('Maatwebsite\Excel\Facades\Excel')) {
        echo "✓ Excel facade class exists<br>";
    } else {
        echo "✗ Excel facade class missing<br>";
    }
    
    if (interface_exists('Maatwebsite\Excel\Concerns\ToModel')) {
        echo "✓ ToModel interface exists<br>";
    } else {
        echo "✗ ToModel interface missing<br>";
    }
    
    if (interface_exists('Maatwebsite\Excel\Concerns\WithHeadingRow')) {
        echo "✓ WithHeadingRow interface exists<br>";
    } else {
        echo "✗ WithHeadingRow interface missing<br>";
    }
    
    // Test composer autoloader
    $loader = require '../vendor/autoload.php';
    if ($loader->findFile('Maatwebsite\Excel\Concerns\ToModel')) {
        echo "✓ Autoloader can find ToModel interface<br>";
    } else {
        echo "✗ Autoloader cannot find ToModel interface<br>";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}

// Now test with Laravel bootstrap
echo "<h3>Testing with Laravel bootstrap...</h3>";

$app = require_once '../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $import = new App\Imports\ProductsImport();
    echo "✓ ProductsImport class works with Laravel bootstrap<br>";
} catch (Exception $e) {
    echo "✗ ProductsImport failed: " . $e->getMessage() . "<br>";
}

echo "<h3>PHP Info</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "GD Extension: " . (extension_loaded('gd') ? 'Loaded' : 'Not Loaded') . "<br>";

?>
