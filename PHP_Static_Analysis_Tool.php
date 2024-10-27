<?php
// Simple PHP Script for detecting SQL Injection vulnerabilities

/**
 * Check if the given line contains SQL injection vulnerable patterns.
 *
 * @param string $line_content The content of the line to check.
 * @return bool True if vulnerable patterns are found, false otherwise.
 */
function is_sql_injection_vulnerable($line_content) {
    // Pattern to detect unsafe SQL function calls with direct user input
    $unsafe_pattern = '/\b(mysql_query|mysqli_query|exec|prepare|query)\s*\(\s*[^()]*\$(\w+)[^()]*\)/i';
    
    // Pattern to detect direct use of user input (like $_GET, $_POST) in SQL functions
    $user_input_pattern = '/\b(mysql_query|mysqli_query|exec|prepare|query)\s*\(\s*(\$_(GET|POST|REQUEST|COOKIE)\[.*?\]|(\$[a-zA-Z_][a-zA-Z0-9_]*))\s*.*?\)/i';

    // Check for unsafe patterns
    if (preg_match($user_input_pattern, $line_content)) {
        return true; // Vulnerable
    }

    // Check for usage of unescaped variables in unsafe SQL function calls
    if (preg_match($unsafe_pattern, $line_content)) {
        return true; // Vulnerable
    }

    // Additional checks can be added here

    return false; // Not vulnerable
}

/**
 * Check for vulnerabilities in the specified PHP file.
 *
 * @param string $filename The path to the PHP file.
 */
function find_vulnerabilities($filename) {
    $vulnerabilities_found = false;

    // Open the file and read line by line
    $handle = fopen($filename, "r");
    if ($handle) {
        $line_number = 0;
        while (($line_content = fgets($handle)) !== false) {
            $line_number++;
            if (is_sql_injection_vulnerable($line_content)) {
                echo "SQL Injection vulnerability detected in $filename on line $line_number: $line_content\n";
                $vulnerabilities_found = true; // Track if any vulnerabilities were found
            }
        }
        fclose($handle);
    } else {
        echo "Error opening file: $filename\n";
        exit(1);
    }

    if (!$vulnerabilities_found) {
        echo "No vulnerabilities found in $filename.\n";
    }
}

// Main execution block
$filename = $argv[1]; // PHP file path passed as argument

if (file_exists($filename)) {
    find_vulnerabilities($filename);
    exit(0); // Exit with zero code if finished checking
} else {
    echo "File not found: $filename\n";
    exit(1); // Exit with non-zero code to indicate failure
}
?>

