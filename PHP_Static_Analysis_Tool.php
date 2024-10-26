<?php
// Simple PHP Script for detecting SQL Injection vulnerability

function is_sql_injection_vulnerable($file_content) {
    $pattern = '/mysql_query\((\"|\').*?(\$\w+).*?(\"|\')\)/'; // Example regex for unsafe SQL queries
    if (preg_match($pattern, $file_content)) {
        return true;
    }
    return false;
}

$filename = $argv[1]; // PHP file path passed as argument
$file_content = file_get_contents($filename);

if (is_sql_injection_vulnerable($file_content)) {
    echo "SQL Injection vulnerability detected in file: $filename\n";
    exit(1); // Exit with non-zero code to indicate failure
} else {
    echo "File is safe: $filename\n";
    exit(0);
}
?>

