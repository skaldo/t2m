<?php

include "define.php";

$isFatalError = FALSE;
$errorOutput = NULL;
$tipOutput = NULL;

//DEFINE ERRORS
define("ERROR_FATAL", "Chyba:", true);
define("ERROR_WARNING", "Upozornění:", true);
define("ERROR_TIP", "Tip:", true);
define("ERROR_EMPTY_INPUT", "Nezapomněli jste na něco?", true);
define("ERROR_UNHANDLED", "Please remember what you were doint and contact administrator<br />To continue reload page or press F5.", true);
define("ERROR_MORSE_T2M_INPUT", "Vstup je pro morseovu abecedu neplatný. Více info.", true);
define("ERROR_MORSE_M2T_INPUT", "Vstup je neplatný. Morseova abeceda pracuje pouze se znaky <b>/</b> ,<b>-</b> a <b>.</b>", true);
define("ERROR_MORSE_M2T_UNRECOGNIZED", "Zadali jste alespoň jedno neplatné písmeno. Více info.", true);
define("ERROR_MORSE_TIP_LIST", "Máte problémy s Morseovou abecedou? Podívejte se na <a href=\"help.html\" onclick=\"return popup('help.html')\">seznam znaků</a>!</span>", true);

function diacriticFree($text) {
    $array = Array('ä' => 'a', 'Ä' => 'A', 'á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'ã' => 'a', 'Ã' => 'A', 'â' => 'a', 'Â' => 'A', 'č' => 'c', 'Č' => 'C', 'ć' => 'c', 'Ć' => 'C', 'ď' => 'd', 'Ď' => 'D', 'ě' => 'e', 'Ě' => 'E', 'é' => 'e', 'É' => 'E', 'ë' => 'e', 'Ë' => 'E', 'è' => 'e', 'È' => 'E', 'ê' => 'e', 'Ê' => 'E', 'í' => 'i', 'Í' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ľ' => 'l', 'Ľ' => 'L', 'ĺ' => 'l', 'Ĺ' => 'L', 'ń' => 'n', 'Ń' => 'N', 'ň' => 'n', 'Ň' => 'N', 'ñ' => 'n', 'Ñ' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ö' => 'o', 'Ö' => 'O', 'ô' => 'o', 'Ô' => 'O', 'ò' => 'o', 'Ò' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ő' => 'o', 'Ő' => 'O', 'ř' => 'r', 'Ř' => 'R', 'ŕ' => 'r', 'Ŕ' => 'R', 'š' => 's', 'Š' => 'S', 'ś' => 's', 'Ś' => 'S', 'ť' => 't', 'Ť' => 'T', 'ú' => 'u', 'Ú' => 'U', 'ů' => 'u', 'Ů' => 'U', 'ü' => 'u', 'Ü' => 'U', 'ù' => 'u', 'Ù' => 'U', 'ũ' => 'u', 'Ũ' => 'U', 'û' => 'u', 'Û' => 'U', 'ý' => 'y', 'Ý' => 'Y', 'ž' => 'z', 'Ž' => 'Z', 'ź' => 'z', 'Ź' => 'Z');
    $temp = strtr($text, $array);
    return $temp;
}

function isFatalError() {
    global $isFatalError;
    if ($isFatalError == TRUE)
        return TRUE;
    else
        return FALSE;
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
    switch ($type) {
        case 0:
            $tipOutput .= "<li><span class=\"tip\">" . ERROR_TIP . " </span>" . $text . "</li>";
            break;

        case 1: //NOT IN USE
            $errorOutput .= "<li><span class=\"error warning\">" . ERROR_WARNING . " </span>" . $text . "</li>";
            break;

        case 2:
            //one fatal is enough. ugh
            if ($isFatalError == TRUE)
                break;

            $errorOutput .="<li><span class=\"error fatal\">" . ERROR_FATAL . " </span>" . $text . "</li>";

            //make other know that we got fatal error over here!
            $isFatalError = TRUE;
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

function t2m($text) {
    $text = diacriticFree($text);

    if (!preg_match('!^[a-zA-Z0-9\?\,\!\.\;\/\=\-\(\)\"\:\_\@\ \n\r]+$!', $text)) {
        doError(ERROR_MORSE_T2M_INPUT, 2);
        doError(ERROR_MORSE_TIP_LIST, 0);
    }

    //do this before the string is splitted
    $getCh = strpos($text, "ch");
    $text = str_split($text);
    if ($getCh !== false) {
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
    for ($i = 0; $i < count($text); $i++) {
        switch ($text[$i]) {
            case "a":case "A": $text[$i] = MORSE_A;
                break;
            case "b":case "B": $text[$i] = MORSE_B;
                break;
            case "c":case "C": $text[$i] = MORSE_C;
                break;
            case "d":case "D": $text[$i] = MORSE_D;
                break;
            case "e":case "E": $text[$i] = MORSE_E;
                break;
            case "f":case "F": $text[$i] = MORSE_F;
                break;
            case "g":case "G": $text[$i] = MORSE_G;
                break;
            case "h":case "H": $text[$i] = MORSE_H;
                break;
            case "ch":case "CH": $text[$i] = MORSE_CH;
                break;
            case "i":case "I": $text[$i] = MORSE_I;
                break;
            case "j":case "J": $text[$i] = MORSE_J;
                break;
            case "k":case "K": $text[$i] = MORSE_K;
                break;
            case "l":case "L": $text[$i] = MORSE_L;
                break;
            case "m":case "M": $text[$i] = MORSE_M;
                break;
            case "n":case "N": $text[$i] = MORSE_N;
                break;
            case "o":case "O": $text[$i] = MORSE_O;
                break;
            case "p":case "P": $text[$i] = MORSE_P;
                break;
            case "q":case "Q": $text[$i] = MORSE_Q;
                break;
            case "r":case "R": $text[$i] = MORSE_R;
                break;
            case "s":case "S": $text[$i] = MORSE_S;
                break;
            case "t":case "T": $text[$i] = MORSE_T;
                break;
            case "u":case "U": $text[$i] = MORSE_U;
                break;
            case "v":case "V": $text[$i] = MORSE_V;
                break;
            case "w":case "W": $text[$i] = MORSE_W;
                break;
            case "x":case "X": $text[$i] = MORSE_X;
                break;
            case "y":case "Y": $text[$i] = MORSE_Y;
                break;
            case "z":case "Z": $text[$i] = MORSE_Z;
                break;
            case " ": $text[$i] = "";
                break;
            case "0": $text[$i] = MORSE_N_0;
                break;
            case "1": $text[$i] = MORSE_N_1;
                break;
            case "2": $text[$i] = MORSE_N_2;
                break;
            case "3": $text[$i] = MORSE_N_3;
                break;
            case "4": $text[$i] = MORSE_N_4;
                break;
            case "5": $text[$i] = MORSE_N_5;
                break;
            case "6": $text[$i] = MORSE_N_6;
                break;
            case "7": $text[$i] = MORSE_N_7;
                break;
            case "8": $text[$i] = MORSE_N_8;
                break;
            case "9": $text[$i] = MORSE_N_9;
                break;
            case "?": $text[$i] = MORSE_S_QUOTATIONMARK;
                break;
            case ",": $text[$i] = MORSE_S_COMMA;
                break;
            case "!": $text[$i] = MORSE_S_EXCLAMATIONMARK;
                break;
            case ".": $text[$i] = MORSE_S_DOT;
                break;
            case ";": $text[$i] = MORSE_S_SEMICOLON;
                break;
            case "/": $text[$i] = MORSE_S_SLASH;
                break;
            case "=": $text[$i] = MORSE_S_EQUAL;
                break;
            case "-": $text[$i] = MORSE_S_DASH;
                break;
            case "  ": $text[$i] = MORSE_S_TAB;
                break;
            case "(": $text[$i] = MORSE_S_BRACKETOPEN;
                break;
            case ")": $text[$i] = MORSE_S_BRACKETCLOSE;
                break;
            case "\"":
            case "„":
            case "“": $text[$i] = MORSE_S_QUOTATIONMARK;
                break;
            case ":": $text[$i] = MORSE_S_COLON;
                break;
            case "_": $text[$i] = MORSE_S_UNDERSCORE;
                break;
            case "@": $text[$i] = MORSE_S_ATSIGN;
                break;
            default: $text[$i] == "*";
                break;
        }
    }

    $return = "";
    foreach ($text as $arr) {
        $return.= "<strong>" . $arr . "</strong> / ";
    }

    return $return;
}

function m2t($text) {
    //strip _all_ whitespaces
    $text = str_replace(" ", "", $text);

    if (preg_match('!^[^\.\/\-]+$!', $text) == 1) {
        doError(ERROR_MORSE_M2T_INPUT, 2);
        doError(ERROR_MORSE_TIP_LIST, 0);
    }

    $text = explode("/", $text);

    for ($i = 0; $i < count($text); $i++) {
        switch ($text[$i]) {
            case MORSE_A: $text[$i] = "a";
                break;
            case MORSE_B: $text[$i] = "b";
                break;
            case MORSE_C: $text[$i] = "c";
                break;
            case MORSE_D: $text[$i] = "d";
                break;
            case MORSE_E: $text[$i] = "e";
                break;
            case MORSE_F: $text[$i] = "f";
                break;
            case MORSE_G: $text[$i] = "g";
                break;
            case MORSE_H: $text[$i] = "h";
                break;
            case MORSE_CH: $text[$i] = "ch";
                break;
            case MORSE_I: $text[$i] = "i";
                break;
            case MORSE_J: $text[$i] = "j";
                break;
            case MORSE_K: $text[$i] = "k";
                break;
            case MORSE_L: $text[$i] = "l";
                break;
            case MORSE_M: $text[$i] = "m";
                break;
            case MORSE_N: $text[$i] = "n";
                break;
            case MORSE_O: $text[$i] = "o";
                break;
            case MORSE_P: $text[$i] = "p";
                break;
            case MORSE_Q: $text[$i] = "q";
                break;
            case MORSE_R: $text[$i] = "r";
                break;
            case MORSE_S: $text[$i] = "s";
                break;
            case MORSE_T: $text[$i] = "t";
                break;
            case MORSE_U: $text[$i] = "u";
                break;
            case MORSE_V: $text[$i] = "v";
                break;
            case MORSE_W: $text[$i] = "w";
                break;
            case MORSE_X: $text[$i] = "x";
                break;
            case MORSE_Y: $text[$i] = "y";
                break;
            case MORSE_Z: $text[$i] = "z";
                break;
            case "": $text[$i] = " ";
                break;
            case MORSE_N_0: $text[$i] = "0";
                break;
            case MORSE_N_1: $text[$i] = "1";
                break;
            case MORSE_N_2: $text[$i] = "2";
                break;
            case MORSE_N_3: $text[$i] = "3";
                break;
            case MORSE_N_4: $text[$i] = "4";
                break;
            case MORSE_N_5: $text[$i] = "5";
                break;
            case MORSE_N_6: $text[$i] = "6";
                break;
            case MORSE_N_7: $text[$i] = "7";
                break;
            case MORSE_N_8: $text[$i] = "8";
                break;
            case MORSE_N_9: $text[$i] = "9";
                break;
            case MORSE_S_QUOTATIONMARK: $text[$i] = "?";
                break;
            case MORSE_S_COMMA: $text[$i] = ",";
                break;
            case MORSE_S_EXCLAMATIONMARK: $text[$i] = "?";
                break;
            case MORSE_S_DOT: $text[$i] = ".";
                break;
            case MORSE_S_SEMICOLON: $text[$i] = ";";
                break;
            case MORSE_S_SLASH: $text[$i] = "/";
                break;
            case MORSE_S_EQUAL: $text[$i] = "=";
                break;
            case MORSE_S_DASH:$text[$i] = ".";
                break;
            case MORSE_S_TAB:$text[$i] = "    ";
                break;
            case MORSE_S_BRACKETOPEN:$text[$i] = "(";
                break;
            case MORSE_S_BRACKETCLOSE:$text[$i] = ")";
                break;
            case MORSE_S_QUOTATIONMARK:$text[$i] = "?";
                break;
            case MORSE_S_COLON:$text[$i] = ":";
                break;
            case MORSE_S_UNDERSCORE:$text[$i] = "_";
                break;
            case MORSE_S_ATSIGN:$text[$i] = "@";
                break;
            default:
                doError(ERROR_MORSE_M2T_UNRECOGNIZED, 2);
                $temp = $text[$i];
                $text[$i] = "*<span class=\"morse_error\">";
                $text[$i] .= $temp;
                $text[$i] .= "</span>*";
                unset($temp);
                break;
        }
    }

    $return = NULL;
    foreach ($text as $arr) {
        $return.= "<strong>" . $arr . "</strong>";
    }

    return $return;
}

function showOutput($text) {

    $return = NULL;
    $text = trim($text);

    if ($text == NULL)
        doError(ERROR_EMPTY_INPUT, 2);

    //we don't want to continue if we have already FE
    global $isFatalError;
    if ($isFatalError == TRUE)
        return;

    //if something goes wrong, just make an error during t2m/m2t
    //check for the only chars available in MorseCode (.-/)
    if (preg_match('/^[\ \-\.\/]+$/i', $text)) {
        $return = m2t($text);
    } else {
        $return = t2m($text);
    }

    return $return;
}

?>
