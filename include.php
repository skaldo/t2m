<?php

/*
 * ***********
 * * Config **
 * ***********
 * input_length:    - max length of input string. If empty, post_max_size in php.ini is used. In bytes.
 *                  - 524288 recommended - 500K
 *                  - no quotes.
 * handle_ch:       - Should we handle Ch diagraph? Boolean.
 *                  - set TRUE for Chamorro, Czech, Slovak, Polish, Igbo, Quechua, Guarani, Welsh, Cornish, Breton and Belarusian Łacinka
 * 
 */

$config = array(
    'input_length' => 524288, //Do not escape by quotes. TODO: Make this safe.
    'handle_ch' => TRUE,
);

/*
 * Config end
 */

require_once "define.php";

$isError = array(
    0 => FALSE, // Tip
    1 => FALSE, // Warning
    2 => FALSE, // Fatal
    3 => FALSE  // show more details, req warning (1) to work
);

$errorOutput = array(
    0 => "", // Tip
    1 => "", // Warn
    2 => "", // Fatal
    3 => ""  // show more details, req warning (1) to work
);

//DEFINE ERRORS
define("ERROR_FATAL", "Chyba:", TRUE);
define("ERROR_WARNING", "Upozornění:", TRUE);
define("ERROR_TIP", "Tip:", TRUE);
define("ERROR_MORE_ERROR_NEAR", "Chyba je poblíž: ", TRUE);
define("ERROR_INPUT_SIZE_EXCEEDED", "Překročili jste maximální velikost vstupu ", TRUE);
define("ERROR_INPUT_EMPTY", "Nezapomněli jste na něco?", TRUE);
define("ERROR_UNHANDLED", "Please remember what you were doint and contact administrator<br />To continue reload page or press F5.", TRUE);

define("ERROR_MORSE_T2M_INPUT", "Vstup je pro morseovu abecedu neplatný. <a class = \"\" href='#' onclick =\"changeVisibility('moreinfo')\">Více info</a>", TRUE);
define("ERROR_MORSE_M2T_UNRECOGNIZED", "Zadali jste alespoň jedno neplatné písmeno. <a class = \"\" href='#' onclick =\"changeVisibility('moreinfo')\">Více info</a>", TRUE);
define("ERROR_MORSE_TIP_LIST", "Máte problémy s Morseovou abecedou? Podívejte se na <a href=\"help.php?type=mo\" onclick=\"return popup('help.php?type=mo')\">seznam znaků</a>!</span>", TRUE);

define("ERROR_BINARY_UNRECOGNIZED", "Neplatný vstup.", TRUE);
define("ERROR_BINARY_TIP_LIST", "Máte problémy se vstupem? Podívejte se na <a href=\"help.php?type=bi\" onclick=\"return popup('help.php?type=bi')\">seznam znaků</a>!</span>", TRUE);

function validateConfig() {
    global $config;
    
    if (
            (!isset($config)) ||
            (!is_array($config)) ||
            (!array_key_exists('input_length', $config)) ||
            (!array_key_exists('handle_ch', $config))
    ) {
        doError('Config broken. Shutting down. #1', 2);
    }

    if (!is_bool($config['handle_ch']) === TRUE) {
        doError('Config broken. Shutting down. #2', 2);
    }
    
    if (!is_int($config['input_length'])) {
        doError('Config broken. Shutting down. #3', 2);
    }
}

function diacriticFree($text) {
    $array = Array('ä' => 'a', 'Ä' => 'A', 'á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'ã' => 'a', 'Ã' => 'A', 'â' => 'a', 'Â' => 'A', 'č' => 'c', 'Č' => 'C', 'ć' => 'c', 'Ć' => 'C', 'ď' => 'd', 'Ď' => 'D', 'ě' => 'e', 'Ě' => 'E', 'é' => 'e', 'É' => 'E', 'ë' => 'e', 'Ë' => 'E', 'è' => 'e', 'È' => 'E', 'ê' => 'e', 'Ê' => 'E', 'í' => 'i', 'Í' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ľ' => 'l', 'Ľ' => 'L', 'ĺ' => 'l', 'Ĺ' => 'L', 'ń' => 'n', 'Ń' => 'N', 'ň' => 'n', 'Ň' => 'N', 'ñ' => 'n', 'Ñ' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ö' => 'o', 'Ö' => 'O', 'ô' => 'o', 'Ô' => 'O', 'ò' => 'o', 'Ò' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ő' => 'o', 'Ő' => 'O', 'ř' => 'r', 'Ř' => 'R', 'ŕ' => 'r', 'Ŕ' => 'R', 'š' => 's', 'Š' => 'S', 'ś' => 's', 'Ś' => 'S', 'ť' => 't', 'Ť' => 'T', 'ú' => 'u', 'Ú' => 'U', 'ů' => 'u', 'Ů' => 'U', 'ü' => 'u', 'Ü' => 'U', 'ù' => 'u', 'Ù' => 'U', 'ũ' => 'u', 'Ũ' => 'U', 'û' => 'u', 'Û' => 'U', 'ý' => 'y', 'Ý' => 'Y', 'ž' => 'z', 'Ž' => 'Z', 'ź' => 'z', 'Ź' => 'Z');
    $return = strtr($text, $array);
    return $return;
}

function isError($type) {
    /*
     * Error types:
     *  0 - Tip
     *  1 - Warning
     *  2 - Fatal Error
     *  3 - Warning: More info
     */
    global $isError;

    if ($isError[$type] == TRUE)
        return TRUE;
    else
        return FALSE;
}

function doError($text, $type) {
    global $isError;
    global $errorOutput;

    //Don't make two same errors
    if ((isError($type) == TRUE) && (strpos($errorOutput[$type], $text) !== FALSE)) {
        return;
    }

    switch ($type) {
        case 0:
            $errorOutput[$type] .= "<li><span class=\"tip\">" . ERROR_TIP . " </span>" . $text . "</li>";
            break;

        case 1:
            $errorOutput[$type] .= "<li><span class=\"error warning\">" . ERROR_WARNING . " </span>" . $text . "</li>";
            break;

        case 2:
            $errorOutput[$type] .= "<li><span class=\"error fatal\">" . ERROR_FATAL . " </span>" . $text . "</li>";
            break;

        case 3:
            $errorOutput[$type] .= "<li>" . $text . "</li>";
            break;

        default:
            die("Unhandled exception #1A." . ERROR_UNHANDLED);
            break;
    }
    $isError[$type] = TRUE;
}

function showError() {
    global $errorOutput;
    $return = "";

    if (isError(2) == TRUE) {
        $return = "<div id=\"error\"><ul>" . $errorOutput[2] . "</ul></div>\n";
    } elseif (isError(1) == TRUE) {
        $return = "<div id=\"error\"><ul>" . $errorOutput[1] . "</ul></div>\n";
        if (isError(3) == TRUE)
            $return .= "<div id=\"moreinfo\" class=\"hidden\"><ul>" . $errorOutput[3] . "</ul><a href=\"#\" class=\"hide\" onclick=\"changeVisibility('moreinfo')\">(Skrýt)</a></div>\n";
    }

    if (isError(0) == TRUE)
        $return .= "<div id=\"tip\"><ul>" . $errorOutput[0] . "</ul></div>\n";

    return $return;
}

function morseCode($input, $encode) {
    //text = input text ; encode==TRUE =>text2morse, FALSE=>morse2text

    global $morse;
    global $config;
    $return = NULL;
    $i = 0;

    if ($encode == TRUE) {
        //MORSECODE ENCODE
        $input = diacriticFree($input);
        $input = strtolower($input);
        $input = preg_replace('!\s+!', ' ', $input); //multiple spaces into one space

        $getCh = strpos($input, "ch");  //do this before the string is splitted
        $input = str_split($input);
        //handling Ch character, skip if ch not found
        if (($getCh !== FALSE) && ($config['handle_ch'] == TRUE)) {
            for ($j = 0; $j < count($input) - 1; $j++) {
                if (($input[$j] == "c") && ($input[$j + 1] == "h")) {
                    $input[$j] = "ch";
                    //delete H and re-index array
                    unset($input[$j + 1]);
                    $input = array_values($input);
                }
            }
        }
        unset($getCh);

        foreach ($input as $temp) {
            //spaces need to have two slashes, make them without spaces - str_replace hack else return character in morseCode
            if (!array_key_exists($temp, $morse)) {
                doError(ERROR_MORSE_T2M_INPUT, 1);
                doError("<span class=\"error\">" . ERROR_MORE_ERROR_NEAR . "</span>" . ($i + 1) . ". písmeno (Neznámý znak <span class=\"red\">" . $temp . "</span>)", 3);
                doError(ERROR_MORSE_TIP_LIST, 0);
                $return .= "<span class=\"red error\" title = '" . ERROR_MORE_ERROR_NEAR . ($i + 1) . ". písmeno (Neznámý znak: " . $temp . ")'>*</span> / ";
            } elseif ($temp == " ") {
                $return .= "/ ";
                $return = str_replace("/ /", "//", $return); //formatting, easier than if next char is space do this....
            } else {
                $return.= $morse[$temp] . " / ";
            }
            $i++;
        }
    } elseif ($encode == FALSE) {
        //MORSECODE DECODE, input check is handled in showOutput()
        if (strpos($input, "/") === FALSE)
            $input = explode(" ", $input);            // split by space
        else {
            $input = str_replace(" ", "", $input);    //get rid of all spaces
            $input = explode("/", $input);            //and split by /
        }

        foreach ($input as $temp) {
            if ((!in_array($temp, $morse))) {
                doError(ERROR_MORSE_M2T_UNRECOGNIZED, 1);
                doError("<span class=\"error\">" . ERROR_MORE_ERROR_NEAR . "</span>" . ($i + 1) . ". písmeno: (Neznámý vstup: <span class=\"red\">" . $temp . "</span>)", 3);
                doError(ERROR_MORSE_TIP_LIST, 0);
                $return .= "<span class=\"red error\" title = '" . ERROR_MORE_ERROR_NEAR . " " . ($i + 1) . ". pismeno (Neznámý vstup: " . $temp . "'>*</span>";
            } else {
                $return.= array_search($temp, $morse);
            }
            $i++;
        }
    } else {
        die("unahdled"); //TODO: UNHANDLED
    }

    return $return;
}

function binaryCode($input, $encode) {
    global $binary;
    $return = NULL;
    $i = 0;

    if ($encode == TRUE) {

        $input = diacriticFree($input);
        $input = preg_replace('!\s+!', ' ', $input); //multiple spaces into one space
        $input = str_split($input);

        foreach ($input as $temp) {
            if (!array_key_exists($temp, $binary)) {
                //TODO: MOREINFO
                doError(ERROR_BINARY_UNRECOGNIZED, 1);
                doError(ERROR_BINARY_TIP_LIST, 0);
                $return .= "<span class=\"red\">ERR</span> ";
            } else {
                $return.= $binary[$temp] . " ";
            }
            $i++;
        }
    } elseif ($encode == FALSE) {

        if (preg_match("/\\s/", $input))
            $input = explode(" ", $input);     // split by space
        else {
            $temp = $input;
            $input = array();
            for ($j = 0; $j < strlen($temp); $j += 8)
                $input[] = substr($temp, $j, 8);
        }
        //Binary DECODE, input check is handled in showOutput()
        foreach ($input as $temp) {
            if ((!in_array($temp, $binary))) {
                //TODO: MOREINFO
                doError(ERROR_BINARY_UNRECOGNIZED, 1);
                doError(ERROR_BINARY_TIP_LIST, 0);
                $return .= "<span class=\"red\">ERR</span> ";
            } else {
                $return.= array_search($temp, $binary);
            }
            $i++;
        }
    } else {
        die("Unhandled"); //TODO: Unhandled
    }

    return $return;
}

function showOutput($input, $type) {
    $return = NULL;
    global $config;
    
    validateConfig();
    
    if ($config['input_length'] != "") {
        //in utf8 "č" is 2 bytes long, but it counts as one
        //if we use latin1, it is converted - so it counts as two
        if (mb_strlen($input, 'latin1') > $config['input_length']) {
            $lengthDiff = ((mb_strlen($input, 'latin1')) - $config['input_length']);

            if ($lengthDiff < 1024) {
                $lengthDiff .= " bytes";
            } elseif (($lengthDiff > 1024) && ($lengthDiff < 1048576)) {
                $lengthDiff = round($lengthDiff / 1024) . " kilobytes";
            } else {
                $lengthDiff = round($lengthDiff / 1024 / 1024) . " megabytes";
            }

            doError(ERROR_INPUT_SIZE_EXCEEDED . "o " . $lengthDiff, 2);
        }
    }

    //Trim some characters like:
    $input = trim($input); //whitespaces at start and end
    $input = str_replace(array("\r\n", "\r", "\n"), ' ', $input); // newlines
    //we don't want to continue if no input or fatal error already
    if ($input == NULL) {
        doError(ERROR_INPUT_EMPTY, 2);
    }
    if (isError(2) == TRUE)
        return;

    switch ($type) {
        case "mo":
            //MORSECODE
            //check for the only chars available in MorseCode (.-/) + \r\n (newline)
            if (preg_match('/^[\ \-\.\/\n\r]+$/i', $input)) {
                $return = morseCode($input, FALSE);
            } else {
                $return = morseCode($input, TRUE);
            }
            break;

        case "bi":
            //BINARY
            //check for the only chars available in binary (01\s)
            if (preg_match('/^[01\ ]+$/i', $input)) {
                $return = binaryCode($input, FALSE);
            } else {
                $return = binaryCode($input, TRUE);
            }
            break;

        default:
            die("Unhandled"); //TODO: UNHANDLED
            break;
    }
    return $return;
}

?>
