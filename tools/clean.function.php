<?php
function cleanInput($input){
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

function cleanNumericInput($input) {
        if ($input === '' || $input === NULL) {
            return false;
        } else { 
            $input = trim($input);
            $input = stripslashes($input);
            $input = htmlspecialchars($input);

            if (is_numeric($input)) {
                return $input;
            } else {
                return false;
            }
        }
    }
?>