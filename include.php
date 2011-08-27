<?php

require_once "define.php";

$isError = array(
    0 => FALSE, // Tip
    1 => FALSE, // Warning
    2 => FALSE, // Fatal
    3 => FALSE, // MoreInfo
);

$errorOutput = array(
    0 => "", // Tip
    1 => "", // Warn
    2 => "", // Fatal
    3 => "", // MoreInfo
);

//DEFINE ERRORS
define("ERROR_FATAL", "Chyba:", TRUE);
define("ERROR_WARNING", "Upozornění:", TRUE);
define("ERROR_TIP", "Tip:", TRUE);
define("ERROR_MORE_ERROR_NEAR", "Chyba je poblíž: ", TRUE);
define("ERROR_INPUT_SIZE_EXCEEDED", "Překročili jste maximální velikost vstupu ", TRUE);
define("ERROR_INPUT_EMPTY", "Nezapomněli jste na něco?", TRUE);
define("ERROR_UNHANDLED", "Please remember what you were doing and contact administrator<br />To continue reload page or press F5.", TRUE);

define("ERROR_MORSE_T2M_INPUT", "Vstup je pro morseovu abecedu neplatný.", TRUE);
define("ERROR_MORSE_M2T_UNRECOGNIZED", "Zadali jste alespoň jedno neplatné písmeno.", TRUE);
define("ERROR_MORSE_TIP_LIST", "Máte problémy s Morseovou abecedou? Podívejte se na <a href=\"help.php?type=mo\" onclick=\"return popup('help.php?type=mo')\">seznam znaků</a>!</span>", TRUE);

define("ERROR_BINARY_UNRECOGNIZED", "Neplatný vstup.", TRUE);
define("ERROR_BINARY_TIP_LIST", "Máte problémy se vstupem? Podívejte se na <a href=\"help.php?type=bi\" onclick=\"return popup('help.php?type=bi')\">seznam znaků</a>!</span>", TRUE);

function checkEverything() {

    //Check Config
    global $config;

    if (
            (!isset($config)) ||
            (!is_array($config)) ||
            (!array_key_exists('input_length', $config)) ||
            (!array_key_exists('handle_ch', $config))
    ) {
        doError(2, "Config broken. Shutting down.", "Config does not exist?");
    }

    if (!is_bool($config['handle_ch'])) {
        doError(2, "Config broken. Shutting down.", "Bad input for 'handle_ch' option.");
    }

    if (!is_int($config['input_length'])) {
        doError(2, "Config broken. Shutting down.", "Bad input for 'input_length' option.");
    }

    if (!is_bool($config['show_gen_time'])) {
        doError(2, "Config broken. Shutting down.", "Bad input for 'show_gen_time' option.");
    }

    //Check inputs
    if (isset($_POST['type'])) {
        switch ($_POST['type']) {
            case "mo":
            case "bi":
                break;
            default:
                doError(2, "Invalid conversion type. Are you injecting via POST?");
                break;
        }
    }
}

function checkInputLength($input) {
    global $config;
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
}

function diacriticFree($text) {
    $array = Array('ä' => 'a', 'Ä' => 'A', 'á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'ã' => 'a', 'Ã' => 'A', 'â' => 'a', 'Â' => 'A', 'č' => 'c', 'Č' => 'C', 'ć' => 'c', 'Ć' => 'C', 'ď' => 'd', 'Ď' => 'D', 'ě' => 'e', 'Ě' => 'E', 'é' => 'e', 'É' => 'E', 'ë' => 'e', 'Ë' => 'E', 'è' => 'e', 'È' => 'E', 'ê' => 'e', 'Ê' => 'E', 'í' => 'i', 'Í' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ľ' => 'l', 'Ľ' => 'L', 'ĺ' => 'l', 'Ĺ' => 'L', 'ń' => 'n', 'Ń' => 'N', 'ň' => 'n', 'Ň' => 'N', 'ñ' => 'n', 'Ñ' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ö' => 'o', 'Ö' => 'O', 'ô' => 'o', 'Ô' => 'O', 'ò' => 'o', 'Ò' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ő' => 'o', 'Ő' => 'O', 'ř' => 'r', 'Ř' => 'R', 'ŕ' => 'r', 'Ŕ' => 'R', 'š' => 's', 'Š' => 'S', 'ś' => 's', 'Ś' => 'S', 'ť' => 't', 'Ť' => 'T', 'ú' => 'u', 'Ú' => 'U', 'ů' => 'u', 'Ů' => 'U', 'ü' => 'u', 'Ü' => 'U', 'ù' => 'u', 'Ù' => 'U', 'ũ' => 'u', 'Ũ' => 'U', 'û' => 'u', 'Û' => 'U', 'ý' => 'y', 'Ý' => 'Y', 'ž' => 'z', 'Ž' => 'Z', 'ź' => 'z', 'Ź' => 'Z');
    $return = strtr($text, $array);
    return $return;
}

function isError($type) {
    global $isError;

    if ($isError[$type] == TRUE)
        return TRUE;
    else
        return FALSE;
}

//Third argument is optional
function doError($type, $text, $more=NULL) {
    global $isError;
    global $errorOutput;

    //If we already have same $text, dont show, but if $more differs, show only $more. else return;
    if ((isError($type) == TRUE) && (strpos($errorOutput[$type], $text) !== FALSE)) {
        if (strpos($errorOutput[3], $more) === FALSE) {
            $errorOutput[3] .= "<li><span class=\"bold\">" . ERROR_MORE_ERROR_NEAR . "</span>" . $more . "</li>";
            $isError[3] = TRUE;
            return;
        }
        else
            return;
    }

    switch ($type) {
        case 0:
            $temp = ERROR_TIP;
            break;
        case 1:
            $temp = ERROR_WARNING;
            break;
        case 2:
            $temp = ERROR_FATAL;
            break;

        default:
            die("Unhandled exception #doError-1." . ERROR_UNHANDLED);
            break;
    }


    $isError[$type] = TRUE;

    if ($more != NULL) {
        $errorOutput[$type] .= "<li><span class=\"bold\">" . $temp . " </span>" . $text;
        $errorOutput[$type] .= " <a class = \"\" href='#' onclick =\"changeVisibility('moreinfo')\">Více info</a>";
        $errorOutput[$type] .= "</li>";

        $errorOutput[3] .= "<li><span class=\"bold\">" . ERROR_MORE_ERROR_NEAR . "</span>" . $more . "</li>";
        $isError[3] = TRUE;
    } else {
        $errorOutput[$type] .= "<li><span class=\"bold\">" . $temp . " </span>" . $text . "</li>";
    }
}

function showError() {
    global $errorOutput;
    $return = "";

    if ((isError(2) == TRUE) || (isError(1) == TRUE)) {
        $return = "<div id=\"error\"><ul>";

        if (isError(1) == TRUE)
            $return .= $errorOutput[1];
        if (isError(2) == TRUE)
            $return .= $errorOutput[2];

        $return .= "</ul></div>\n";

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

        if ($config['handle_ch'] == TRUE)
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
                doError(1, ERROR_MORSE_T2M_INPUT, ($i + 1) . ". písmeno (Neznámý znak <span class=\"red\">" . $temp . "</span>)");
                doError(0, ERROR_MORSE_TIP_LIST);
                $return .= "<span class=\"red bold\" title = '" . ERROR_MORE_ERROR_NEAR . ($i + 1) . ". písmeno (Neznámý znak: " . $temp . ")'>*</span> / ";
            } elseif ($temp == " ") {
                $return .= "/ ";
            } else {
                $return.= $morse[$temp] . " / ";
            }
            $i++;
        }
        $return = str_replace("/ /", "//", $return); //formatting, easier than if next char is space do this....
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
                doError(1, ERROR_MORSE_M2T_UNRECOGNIZED, "<span class=\"error\">" . ERROR_MORE_ERROR_NEAR . "</span>" . ($i + 1) . ". písmeno: (Neznámý vstup: <span class=\"red\">" . $temp . "</span>)");
                doError(0, ERROR_MORSE_TIP_LIST, 0);
                $return .= "<span class=\"red bold\" title = '" . ERROR_MORE_ERROR_NEAR . " " . ($i + 1) . ". pismeno (Neznámý vstup: " . $temp . "'>*</span>";
            } else {
                $return.= array_search($temp, $morse);
            }
            $i++;
        }
    } else {
        die("Unhandled exception #MorseCode-1." . ERROR_UNHANDLED);
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
                doError(1, ERROR_BINARY_UNRECOGNIZED);
                doError(0, ERROR_BINARY_TIP_LIST);
                $return .= "<span class=\"red\">ERR</span> ";
            } else {
                $return.= $binary[$temp] . " ";
            }
            $i++;
        }
    } elseif ($encode == FALSE) {
        //Binary DECODE, input check is handled in showOutput()
        //delete spaces and explode every 8 chars
        $input = str_replace(" ", "", $input);
        $temp = $input;
        $input = array();
        for ($j = 0; $j < strlen($temp); $j += 8)
            $input[] = substr($temp, $j, 8);

        foreach ($input as $temp) {
            if ((!in_array($temp, $binary))) {
                //TODO: MOREINFO
                doError(0, ERROR_BINARY_UNRECOGNIZED);
                doError(1, ERROR_BINARY_TIP_LIST);
                $return .= "<span class=\"red\">ERR</span> ";
            } else {
                $return.= array_search($temp, $binary);
            }
        }
    } else {
        die("Unhandled #binaryCode-1" . ERROR_UNHANDLED);
    }

    return $return;
}

function showOutput($input, $type) {
    $return = NULL;
    global $config;

    checkEverything();
    checkInputLength($input);

    if ($input == NULL)
        doError(2, ERROR_INPUT_EMPTY);

    //Don't bother doing anything if already fatal error
    if (isError(2) == TRUE)
        return;

    //Trim whitespaces at start/end and newlines
    $input = trim($input);
    $input = str_replace(array("\r\n", "\r", "\n"), ' ', $input);

    switch ($type) {
        /*
         * mo - text2morsecode
         * bi - text2binary
         */
        case "mo":
            //check for the only chars available in MorseCode (.-/) + \r\n (newline)
            if (preg_match('/^[\ \-\.\/\n\r]+$/i', $input)) {
                $return = morseCode($input, FALSE);
            } else {
                $return = morseCode($input, TRUE);
            }
            break;

        case "bi":
            //check for the only chars available in binary (0, 1 and \s )
            if (preg_match('/^[01\ ]+$/i', $input)) {
                $return = binaryCode($input, FALSE);
            } else {
                $return = binaryCode($input, TRUE);
            }
            break;

        default:
            die("Unhandled exception #showOutput-1." . ERROR_UNHANDLED);
            break;
    }
    return $return;
}

?>
