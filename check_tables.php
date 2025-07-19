<?php
require_once 'vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=harvin', 'root', '');
    
    echo "=== Database Tables Check ===\n\n";
    
    // Check all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "All tables:\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }
    
    echo "\n=== Admin Tables ===\n";
    $adminTables = array_filter($tables, function($table) {
        return strpos($table, 'admin_') === 0;
    });
    
    foreach ($adminTables as $table) {
        echo "- $table\n";
    }
    
    echo "\n=== Required Tables Check ===\n";
    $requiredTables = [
        'admin_permissions',
        'admin_roles', 
        'admin_role_user',
        'admin_permission_role'
    ];
    
    foreach ($requiredTables as $table) {
        $exists = in_array($table, $tables);
        echo "- $table: " . ($exists ? "✅ EXISTS" : "❌ MISSING") . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 