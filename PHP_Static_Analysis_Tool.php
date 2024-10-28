<?php
/**
 * Check if the given line contains SQL injection vulnerable patterns.
 *
 * @param string $line_content The content of the line to check.
 * @return string|null Detailed message if vulnerable patterns are found, null otherwise.
 */
function is_sql_injection_vulnerable($line_content) {
    $sql_injection_explanation = "SQL Injection is a security vulnerability that occurs when an application allows users to control SQL queries, potentially leading to unauthorized data access or database corruption.";

    $user_input_pattern = '/\b(mysql_query|mysqli_query|pg_query|exec|query|PDO::query|PDO::exec)\s*\(\s*(\$_(GET|POST|REQUEST|COOKIE|SESSION|FILES)\[.*?\]|(\$[a-zA-Z_][a-zA-Z0-9_]*))\s*.*?\)/i';
    $unsafe_pattern = '/\b(mysql_query|mysqli_query|pg_query|exec|query|PDO::query|PDO::exec)\s*\(\s*[^()]*\$(\w+)[^()]*\)/i';

    if (preg_match('/->prepare\(\s*".*?"\s*\)/i', $line_content) || preg_match('/->bindParam|->bindValue/', $line_content)) {
        return null;
    }

    if (preg_match($user_input_pattern, $line_content)) {
        return "Vulnerability: Direct user input in SQL function.\n" .
               "$sql_injection_explanation\n" .
               "Reason: Direct user input is used in an SQL function without using parameterized queries or prepared statements, allowing attackers to inject malicious SQL.\n" .
               "Solution: Use prepared statements with parameterized queries to handle user input.";
    }

    if (preg_match($unsafe_pattern, $line_content)) {
        return "Vulnerability: Unescaped variable in SQL function.\n" .
               "$sql_injection_explanation\n" .
               "Reason: An unescaped variable is used in an SQL function, potentially allowing SQL injection.\n" .
               "Solution: Sanitize input and use prepared statements.";
    }

    if (preg_match('/\b(?:SELECT|INSERT|UPDATE|DELETE)\s.*\s*WHERE\s/i', $line_content) && preg_match('/\$[a-zA-Z_][a-zA-Z0-9_]*/', $line_content)) {
        return "Potential vulnerability: Raw SQL query with dynamic input.\n" .
               "$sql_injection_explanation\n" .
               "Reason: Raw SQL query with variables detected. If untrusted input is included, it could allow SQL injection.\n" .
               "Solution: Use parameterized queries with prepared statements to prevent injection.";
    }

    return null;
}

/**
 * Check for vulnerabilities in the specified PHP file and provide detailed explanations.
 *
 * @param string $filename The path to the PHP file.
 * @return bool True if vulnerabilities were found, false otherwise.
 */
function find_vulnerabilities($filename) {
    $vulnerabilities_found = false;

    $handle = fopen($filename, "r");
    if ($handle) {
        $line_number = 0;
        while (($line_content = fgets($handle)) !== false) {
            $line_number++;
            $vulnerability_message = is_sql_injection_vulnerable($line_content);
            if ($vulnerability_message) {
                echo "Vulnerability detected in file: $filename on line $line_number.\n";
                echo "Details: $vulnerability_message\n";
                echo "Code: $line_content\n\n";
                ob_flush(); // Flushes the output buffer
                flush();    // Flushes the system output buffer
                $vulnerabilities_found = true;
            }
        }
        fclose($handle);
    } else {
        echo "Error opening file: $filename\n";
        ob_flush();
        flush();
        exit(1);
    }

    if (!$vulnerabilities_found) {
        echo "No vulnerabilities found in $filename.\n";
        ob_flush();
        flush();
    }
    
    return $vulnerabilities_found;
}

// Main execution block
$filename = $argv[1];

if (file_exists($filename)) {
    $vulnerabilities_found = find_vulnerabilities($filename);
    exit($vulnerabilities_found ? 1 : 0);
} else {
    echo "File not found: $filename\n";
    ob_flush();
    flush();
    exit(1);
}

?>

