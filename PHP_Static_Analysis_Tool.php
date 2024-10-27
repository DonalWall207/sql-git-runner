<?php
// Improved PHP Script for detecting SQL Injection vulnerabilities

/**
 * Check if the given line contains SQL injection vulnerable patterns.
 *
 * @param string $line_content The content of the line to check.
 * @return bool True if vulnerable patterns are found, false otherwise.
 */
function is_sql_injection_vulnerable($line_content) {
    // Pattern to detect direct use of user input in SQL functions (ignoring prepared statements)
    $user_input_pattern = '/\b(mysql_query|mysqli_query|pg_query|exec|query|PDO::query|PDO::exec)\s*\(\s*(\$_(GET|POST|REQUEST|COOKIE|SESSION|FILES)\[.*?\]|(\$[a-zA-Z_][a-zA-Z0-9_]*))\s*.*?\)/i';
    
    // Pattern to detect unsafe SQL function calls (ignoring prepared statements)
    $unsafe_pattern = '/\b(mysql_query|mysqli_query|pg_query|exec|query|PDO::query|PDO::exec)\s*\(\s*[^()]*\$(\w+)[^()]*\)/i';
    
    // Check for prepared statements and parameter binding, ignore if safe
    if (preg_match('/->prepare\(\s*"(.*?)"\s*\)/', $line_content)) {
        return false; // Ignore prepared statements
    }

    // Check for unsafe patterns
    if (preg_match($user_input_pattern, $line_content)) {
        return true; // Vulnerable
    }

    // Check for usage of unescaped variables in unsafe SQL function calls
    if (preg_match($unsafe_pattern, $line_content)) {
        return true; // Vulnerable
    }

    // Additional checks for raw queries
    if (preg_match('/\b(?:SELECT|INSERT|UPDATE|DELETE)\s.*\s*WHERE\s/i', $line_content) && preg_match('/\$[a-zA-Z_][a-zA-Z0-9_]*/', $line_content)) {
        return true; // Possible vulnerable raw SQL query
    }

    return false; // Not vulnerable
}

/**
 * Check for vulnerabilities in the specified PHP file.
 *
 * @param string $filename The path to the PHP file.
 * @return bool True if vulnerabilities were found, false otherwise.
 */
function find_vulnerabilities($filename) {
    $vulnerabilities_found = false; // Initialize the variable

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
    
    return $vulnerabilities_found; // Return the result
}

// Main execution block
$filename = $argv[1]; // PHP file path passed as argument

if (file_exists($filename)) {
    $vulnerabilities_found = find_vulnerabilities($filename); // Capture the return value
    exit($vulnerabilities_found ? 1 : 0); // Use the captured value for exit code
} else {
    echo "File not found: $filename\n";
    exit(1); // Exit with non-zero code to indicate failure
}
?>

