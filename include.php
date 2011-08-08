<?php

include "define.php";

$isFatalError = FALSE;
$isWarningError = FALSE;
$errorOutput = NULL;
$tipOutput = NULL;

//DEFINE ERRORS
define("ERROR_FATAL", "Chyba:", TRUE);
define("ERROR_WARNING", "Upozornění:", TRUE);
define("ERROR_TIP", "Tip:", TRUE);
define("ERROR_EMPTY_INPUT", "Nezapomněli jste na něco?", TRUE);
define("ERROR_UNHANDLED", "Please remember what you were doint and contact administrator<br />To continue reload page or press F5.", TRUE);
define("ERROR_MORSE_T2M_INPUT", "Vstup je pro morseovu abecedu neplatný. Více info.", TRUE);
define("ERROR_MORSE_M2T_INPUT", "Vstup je neplatný. Morseova abeceda pracuje pouze se znaky <b>/</b> ,<b>-</b> a <b>.</b>", TRUE);
define("ERROR_MORSE_M2T_UNRECOGNIZED", "Zadali jste alespoň jedno neplatné písmeno. Více info.", TRUE);
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
     */
    global $isFatalError;
    global $isWarningError;

    switch ($type) {
        case 1:
            if ($isWarningError == TRUE) {
                return TRUE;
                break;
            } else {
                return FALSE;
                break;
            }
            break;
        case 2:
            if ($isFatalError == TRUE) {
                return TRUE;
                break;
            } else {
                return FALSE;
                break;
            }
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
     */

    global $errorOutput;
    global $tipOutput;
    global $isFatalError;
    global $isWarningError;

    switch ($type) {
        case 0:
            $tipOutput .= "<li><span class=\"tip\">" . ERROR_TIP . " </span>" . $text . "</li>";
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

        default:
            die("Unhandled exception #1A." . ERROR_UNHANDLED);
            break;
    }
}

function showError($type) {
    global $errorOutput;
    global $tipOutput;
    $return = NULL; //In case there is no error

    switch ($type) {
        case 0:
            if ($tipOutput != NULL)
                $return = "<div id=\"tip\"><ul>" . $tipOutput . "</ul></div>";
            break;

        case 1:
        case 2:
            if ($errorOutput != NULL)
                $return = "<div id=\"error\"><ul>" . $errorOutput . "</ul></div>";
            break;

        default:
            die("Unhandled exception #1B." . ERROR_UNHANDLED);
            break;
    }
    return $return;
}

//text = input text ; encode==TRUE =>text2morse, FALSE=>morse2text
function morseCode($text, $encode) {
    global $morse;
    global $morse_buffer;
    $return = NULL;

    if ($encode == TRUE) {
        //MORSECODE ENCODE
        $text = diacriticFree($text);

        //if input is bad, make error,tip and return zero.
        if (!preg_match('!^[a-zA-Z0-9\?\,\!\.\;\/\=\-\(\)\"\:\_\@\ \n\r]+$!', $text)) {
            doError(ERROR_MORSE_T2M_INPUT, 2);
            doError(ERROR_MORSE_TIP_LIST, 0);
            return 0;
        }

        //do this before the string is splitted
        //until unset we handle Ch character.
        $getCh = strpos($text, "ch");
        $text = str_split($text); //the only exception
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
            
            //spaces need to have two slashes, make them without spaces - str_replace hack
            //else return character in morseCode
            if ($morse[$temp][1] == "/") {
                $return.= "/ ";
                $return = str_replace(" / / ", " // ", $return);
            } else {
                $return.= $morse[$temp][1] . " / ";
            }
        }
    } else {

        //MORSECODE DECODE
        //strip _all_ whitespaces
        $text = str_replace(" ", "", $text);

        //preg_match is already handled in showOutput(), so this shoul never happen
        if (preg_match('!^[^\.\/\-]+$!', $text) == 1) {
            doError(ERROR_MORSE_M2T_INPUT, 2);
            doError(ERROR_MORSE_TIP_LIST, 0);
        }

        $text = explode("/", $text);

        foreach ($text as $temp) {
            //IF NOT RECOGNIZED and it is not empty value make WarningError
            if ((!in_array($temp, $morse_buffer)) && ($temp !== "")) {
                $return .= "*<span class=\"red error\">" . $temp . "</span>*";
                doError(ERROR_MORSE_M2T_UNRECOGNIZED, 1);
            }

            //IF SPACE
            if ($temp == "") {
                $return .= " ";
            } else {
                foreach ($morse as $temp2) {
                    if ($temp == $temp2[1]) {
                        $return .= $temp2[0];
                    }
                }
            }
        }
        //Comment this for uppercase output
        $return = strtolower($return);
    }
    return $return;
}

function showOutput($text) {

    $return = NULL;

    //Trim some characters like:
    $text = trim($text); //whitespaces at start and end
    $text = str_replace(array("\r\n", "\r", "\n"), ' ', $text); // newlines
    $text = preg_replace("/\s\s+/", " ", $text); //multiple spaces

    if ($text == NULL)
        doError(ERROR_EMPTY_INPUT, 2);

    //we don't want to continue if we have already Fatal Error
    global $isFatalError;
    if ($isFatalError == TRUE)
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
