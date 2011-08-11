<?php

/*
 * ***********
 * * Config **
 * ***********
 * input_length:  - max length of input string. If empty, post_max_size in php.ini is used. In bytes.
 * 
 */

$config['input_length'] = "1048576"; //1M - that is about 850k chars in czech (really? :) )

/*
 * Config end
 */

include "define.php";

$isFatalError = FALSE;
$isWarningError = FALSE;
$isTipError = FALSE;
$isMoreError = FALSE;
$errorOutput = NULL;
$tipOutput = NULL;
$moreOutput = NULL;

//DEFINE ERRORS
define("ERROR_FATAL", "Chyba:", TRUE);
define("ERROR_WARNING", "Upozornění:", TRUE);
define("ERROR_TIP", "Tip:", TRUE);
define("ERROR_INPUT_SIZE_EXCEEDED", "Překročili jste maximální velikost vstupu ", TRUE);
define("ERROR_INPUT_EMPTY", "Nezapomněli jste na něco?", TRUE);
define("ERROR_UNHANDLED", "Please remember what you were doint and contact administrator<br />To continue reload page or press F5.", TRUE);
define("ERROR_MORSE_T2M_INPUT", "Vstup je pro morseovu abecedu neplatný. <a class = \"\" href='#' onclick =\"changeVisibility('moreinfo')\">Více info</a>", TRUE);
define("ERROR_MORSE_M2T_UNRECOGNIZED", "Zadali jste alespoň jedno neplatné písmeno. <a class = \"\" href='#' onclick =\"changeVisibility('moreinfo')\">Více info</a>", TRUE);
define("ERROR_MORSE_TIP_LIST", "Máte problémy s Morseovou abecedou? Podívejte se na <a href=\"help.html\" onclick=\"return popup('help.html')\">seznam znaků</a>!</span>", TRUE);

function diacriticFree($text) {
    $array = Array('ä' => 'a', 'Ä' => 'A', 'á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'ã' => 'a', 'Ã' => 'A', 'â' => 'a', 'Â' => 'A', 'č' => 'c', 'Č' => 'C', 'ć' => 'c', 'Ć' => 'C', 'ď' => 'd', 'Ď' => 'D', 'ě' => 'e', 'Ě' => 'E', 'é' => 'e', 'É' => 'E', 'ë' => 'e', 'Ë' => 'E', 'è' => 'e', 'È' => 'E', 'ê' => 'e', 'Ê' => 'E', 'í' => 'i', 'Í' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ľ' => 'l', 'Ľ' => 'L', 'ĺ' => 'l', 'Ĺ' => 'L', 'ń' => 'n', 'Ń' => 'N', 'ň' => 'n', 'Ň' => 'N', 'ñ' => 'n', 'Ñ' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ö' => 'o', 'Ö' => 'O', 'ô' => 'o', 'Ô' => 'O', 'ò' => 'o', 'Ò' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ő' => 'o', 'Ő' => 'O', 'ř' => 'r', 'Ř' => 'R', 'ŕ' => 'r', 'Ŕ' => 'R', 'š' => 's', 'Š' => 'S', 'ś' => 's', 'Ś' => 'S', 'ť' => 't', 'Ť' => 'T', 'ú' => 'u', 'Ú' => 'U', 'ů' => 'u', 'Ů' => 'U', 'ü' => 'u', 'Ü' => 'U', 'ù' => 'u', 'Ù' => 'U', 'ũ' => 'u', 'Ũ' => 'U', 'û' => 'u', 'Û' => 'U', 'ý' => 'y', 'Ý' => 'Y', 'ž' => 'z', 'Ž' => 'Z', 'ź' => 'z', 'Ź' => 'Z');
    $temp = strtr($text, $array);
    return $temp;
}

function isError($type) {
    /*
     * Error types:
     *  0 - Tip
     *  1 - Warning
     *  2 - Fatal Error
     *  3 - Warning: More info
     */
    global $isFatalError;
    global $isWarningError;
    global $isTipError;
    global $isMoreError;

    switch ($type) {
        case 0:
            if ($isTipError == TRUE)
                return TRUE;
            else
                return FALSE;

            break;

        case 1:
            if ($isWarningError == TRUE)
                return TRUE;
            else
                return FALSE;

            break;

        case 2:
            if ($isFatalError == TRUE)
                return TRUE;
            else
                return FALSE;

            break;

        case 3:
            if ($isMoreError == TRUE)
                return TRUE;
            else
                return FALSE;
            break;

        default:
            break;
    }
}

function doError($text, $type) {
    /*
     * Error types:
     *  0 - Tip
     *  1 - Warning - CURRENTLY_NOT_USED
     *  2 - Fatal Error
     *  3 - Warning: More info
     */

    global $errorOutput;
    global $tipOutput;
    global $moreOutput;
    global $isFatalError;
    global $isWarningError;
    global $isMoreError;
    global $isTipError;

    switch ($type) {
        case 0:
            if ((isError(0) == TRUE) && (strpos($tipOutput, $text) !== FALSE)) {
                //Do nothing.
            } else {
                $tipOutput .= "<li><span class=\"tip\">" . ERROR_TIP . " </span>" . $text . "</li>";
                $isTipError = TRUE;
            }
            break;

        case 1:
            //Don't make 2 same warnings.
            if ((isError(1) == TRUE) && (strpos($errorOutput, $text) !== FALSE)) {
                //Do nothing.
            } else {
                $errorOutput .= "<li><span class=\"error warning\">" . ERROR_WARNING . " </span>" . $text . "</li>";
                $isWarningError = TRUE; //make other know that we got warning error over here!
            }
            break;

        case 2:
            //Don't make 2 same errors.
            if ((isError(2) == TRUE) && (strpos($errorOutput, $text) !== FALSE)) {
                //Do nothing.
            } else {
                $errorOutput .="<li><span class=\"error fatal\">" . ERROR_FATAL . " </span>" . $text . "</li>";
                $isFatalError = TRUE;
            }
            break;

        case 3:
            //Don't make 2 same errors.
            if ((isError(3) == TRUE) && (strpos($moreOutput, $text) !== FALSE)) {
                //Do nothing.
            } else {
                $moreOutput .="<li>" . $text . "</li>";
                $isMoreError = TRUE;
            }
            break;

        default:
            die("Unhandled exception #1A." . ERROR_UNHANDLED);
            break;
    }
}

function showError() {
    global $errorOutput;
    global $tipOutput;
    global $moreOutput;

    $return = "";

    if (isError(2) == TRUE) {
        $return = "<div id=\"error\"><ul>" . $errorOutput . "</ul></div>\n";
    } elseif (isError(1) == TRUE) {
        $return .= "<div id=\"error\"><ul>" . $errorOutput . "</ul></div>\n";
        if (isError(3) == TRUE)
            $return .= "<div id=\"moreinfo\" class=\"hidden\"><ul>" . $moreOutput . "</ul><span class=\"hide\" onclick=\"changeVisibility('moreinfo')\">(Skrýt)</span></div>\n";
    }

    if (isError(0) == TRUE)
        $return .= "<div id=\"tip\"><ul>" . $tipOutput . "</ul></div>\n";

    return $return;
}

function morseCode($text, $encode) {
    //text = input text ; encode==TRUE =>text2morse, FALSE=>morse2text

    global $morse;
    global $morse_buffer;
    $return = NULL;
    $i = 0;

    if ($encode == TRUE) {
        //MORSECODE ENCODE
        $text = diacriticFree($text);
        $text = strtolower($text);

        $getCh = strpos($text, "ch");  //do this before the string is splitted
        $text = str_split($text); //the only exception
        //handling Ch character
        if ($getCh !== FALSE) {
            for ($i = 0; $i < count($text) - 1; $i++) {
                if (($text[$i] == "c") && ($text[$i + 1] == "h")) {
                    $text[$i] = "ch";
                    //delete H and re-index array
                    unset($text[$i + 1]);
                    $text = array_values($text);
                }
            }
        }
        unset($getCh);

        foreach ($text as $temp) {
            //spaces need to have two slashes, make them without spaces - str_replace hack else return character in morseCode
            if (!array_key_exists($temp, $morse)) {
                doError(ERROR_MORSE_T2M_INPUT, 1);
                doError("<span class=\"red error\">Chyba je poblíž: </span>" . ($i + 1) . ". písmeno (<span class=\"red\">" . $temp . "</span>)", 3);
                $return .= "<span class=\"red error\" title = 'Chyba je pobliz: " . ($i + 1) . "'>*</span>";
                doError(ERROR_MORSE_TIP_LIST, 0);

                if (($i + 1) != count($text)) {
                    $return .= " / ";
                }
            } elseif ($morse[$temp] == "/") {
                $return.= "/ ";
                $return = str_replace(" / / ", " // ", $return);
            } else {
                $return.= $morse[$temp] . " / ";
            }
            $i++;
        }
    } else {

        //MORSECODE DECODE, input check is handled in showOutput()
        if (strpos($text, "/") === false)
            $text = explode(" ", $text);            // split by space
        else {
            $text = str_replace(" ", "", $text);    //get rid of all spaces
            $text = explode("/", $text);            //and split by /
        }

        foreach ($text as $temp) {
            //IF SPACE
            if ($temp == "") {
                $return .= " ";
            } elseif ((!in_array($temp, $morse))) {
                $return .= "<span class=\"red error\" title = 'Chyba je pobliz: " . ($i + 1) . ". pismeno'>*</span>";
                doError(ERROR_MORSE_M2T_UNRECOGNIZED, 1);
                doError("<span class=\"red error\">Chyba je poblíž: </span>" . ($i + 1) . ". písmeno: (<span class=\"red\">" . $temp . "</span>)", 3);
                doError(ERROR_MORSE_TIP_LIST, 0);
            } elseif (in_array($temp, $morse)) {
                $return.= array_search($temp, $morse);
            }
            $i++;
        }
    }
    return $return;
}

function showOutput($text) {
    $return = NULL;
    global $config;

    if ((isset($config['input_length'])) && ($config['input_length'] != "")) {
        //in utf8 "č" is 2 bytes long, but it counts as one
        //if we use latin1, it is converted - so it counts as two
        if (mb_strlen($text, 'latin1') > $config['input_length']) {
            $lengthDiff = ((mb_strlen($text, 'latin1')) - $config['input_length']);
            
            if ($lengthDiff < 1024) {
                $lengthDiff .= " bytes";
            } elseif (($rozdil > 1024) && ($lengthDiff < 1048576)) {
                $lengthDiff = round($lengthDiff / 1024) . " kilobytes";
            } else {
                $lengthDiff = round($lengthDiff / 1024 / 1024) . " megabytes";
            }
            
            doError(ERROR_INPUT_SIZE_EXCEEDED . "o " . $lengthDiff, 2);
        }
    }

    //Trim some characters like:
    $text = trim($text); //whitespaces at start and end
    $text = str_replace(array("\r\n", "\r", "\n"), ' ', $text); // newlines
    //we don't want to continue if we have already Fatal Error or if we encounter this
    if ($text == NULL) {
        doError(ERROR_INPUT_EMPTY, 2);
        return;
    }

    if (isError(2) == TRUE)
        return;

    //MORSECODE
    //if something goes wrong, just make an error during t2m/m2t
    //check for the only chars available in MorseCode (.-/) + \r\n (newline)
    if (preg_match('/^[\ \-\.\/\n\r]+$/i', $text)) {
        $return = morseCode($text, FALSE);
    } else {
        $return = morseCode($text, TRUE);
    }

    return $return;
}

?>
