<?php
function cleanInput($input) {
    if (is_null($input)) {
        return ''; // Return an empty string if the input is null
    }
    if (!is_string($input)) {
        return $input; // If not a string, return as-is
    }
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}


function cleanNumericInput($input) {
    if (is_null($input) || $input === '') {
        return null; // Return null for empty inputs
    }
    if (is_numeric($input)) {
        return $input; // Return numeric value
    }
    return null; // Return null if not numeric
}

?>