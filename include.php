<?php

/*
  //DEFINE OUTPUT
  //output for t2m. Put here whatever char you like, just don't make dot appear like dash :)
  define("OUTPUT_DOT", "&#149;", true);
  define("OUTPUT_DASH", "&#151;", true);
  define("OUTPUT_SLASH", "/", true);
 */

//DEFINE MORSECODE
define("MORSE_A", ".-", true);      //Akát
define("MORSE_B", "-...", true);    //Blýskavice
define("MORSE_C", "-.-.", true);    //Cílovníci
define("MORSE_D", "-..", true);     //Dálava
define("MORSE_E", ".", true);       //Erb
define("MORSE_F", "..-.", true);    //Filipíny
define("MORSE_G", "--.", true);     //Grónskázem
define("MORSE_H", "....", true);    //Hrachovina
define("MORSE_CH", "----", true);   //Chvátámkvámsám
define("MORSE_I", "..", true);      //Ibis
define("MORSE_J", ".---", true);    //Jasmínbílý
define("MORSE_K", "-.-", true);     //Krákorá
define("MORSE_L", ".-..", true);    //Lupíneček
define("MORSE_M", "--", true);      //Mává
define("MORSE_N", "-.", true);      //Nástup
define("MORSE_O", "---", true);     //Ónášpán
define("MORSE_P", ".--.", true);    //Papírníci
define("MORSE_Q", "--.-", true);    //Kvílíorkán
define("MORSE_R", ".-.", true);     //Rarášek
define("MORSE_S", "...", true);     //Sobota
define("MORSE_T", "-", true);       //Tón
define("MORSE_U", "..-", true);     //Uličník
define("MORSE_V", "...-", true);    //Vyučený
define("MORSE_W", ".--", true);     //Wagónklád
define("MORSE_X", "-..-", true);    //Xénokratés
define("MORSE_Y", "-.--", true);    //Ýgarmává
define("MORSE_Z", "--..", true);    //Známážena
define("MORSE_SPACE", "/", true);
define("MORSE_N_0", ".----");
define("MORSE_N_1", "..---");
define("MORSE_N_2", "...--");
define("MORSE_N_3", "....-");
define("MORSE_N_4", ".....");
define("MORSE_N_5", "-....");
define("MORSE_N_6", "--...");
define("MORSE_N_7", "---..");
define("MORSE_N_8", "----.");
define("MORSE_N_9", "-----");
define("MORSE_S_QUESTIONMARK", "..--..");   // ?
define("MORSE_S_COMMA", "--..--");          // ,
define("MORSE_S_EXCLAMATIONMARK", "--...-");// !
define("MORSE_S_DOT", ".-.-.-");            // .
define("MORSE_S_SEMICOLON", "-.-.-.");      // ;
define("MORSE_S_SLASH", "-..-.");           // /
define("MORSE_S_EQUAL", "-...-");           // =
define("MORSE_S_DASH", "-....-");           // -
define("MORSE_S_TAB", ".----.");            // \t
define("MORSE_S_BRACKETOPEN", "-.--.");     // (
define("MORSE_S_BRACKETCLOSE", "-.--.-");   // )
define("MORSE_S_QUOTATIONMARK", ".-..-.");  // "
define("MORSE_S_COLON", "---...");          // :
define("MORSE_S_UNDERSCORE", "..--.-");     // _
define("MORSE_S_ATSIGN", ".--.-.");         // @
//DEFINE ERRORS
//error texts
define("ERROR_FATAL", "Chyba:", true);
define("ERROR_WARNING", "Upozornění:", true);
define("ERROR_T2M", "Vstup je pro morseovu abecedu neplatný.", true);
define("ERROR_M2T", "Vstup je neplatný. Morseova abeceda pracuje pouze se znaky <b>/</b> ,<b>-</b> a <b>.</b>", true);
define("ERROR_M2T_UNRECOGNIZED", "Zadali jste alespoň jedno neplatné písmeno, bude zobrazeno mezi hvězdičkami.", true);
define("ERROR_EMPTY_INPUT", "Nezapomněli jste na něco?", true);
define("ERROR_TIP_LIST", "<br /><span class=\"tip\"><b>Tip:</b> Máte problémy s Morseovou abecedou? Podívejte se na <a href=\"help.html\" onclick=\"return popup('help.html')\">seznam znaků</a>!</span>", true);

function diacriticFree($text) {
    $array = Array('ä' => 'a', 'Ä' => 'A', 'á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'ã' => 'a', 'Ã' => 'A', 'â' => 'a', 'Â' => 'A', 'č' => 'c', 'Č' => 'C', 'ć' => 'c', 'Ć' => 'C', 'ď' => 'd', 'Ď' => 'D', 'ě' => 'e', 'Ě' => 'E', 'é' => 'e', 'É' => 'E', 'ë' => 'e', 'Ë' => 'E', 'è' => 'e', 'È' => 'E', 'ê' => 'e', 'Ê' => 'E', 'í' => 'i', 'Í' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ľ' => 'l', 'Ľ' => 'L', 'ĺ' => 'l', 'Ĺ' => 'L', 'ń' => 'n', 'Ń' => 'N', 'ň' => 'n', 'Ň' => 'N', 'ñ' => 'n', 'Ñ' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ö' => 'o', 'Ö' => 'O', 'ô' => 'o', 'Ô' => 'O', 'ò' => 'o', 'Ò' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ő' => 'o', 'Ő' => 'O', 'ř' => 'r', 'Ř' => 'R', 'ŕ' => 'r', 'Ŕ' => 'R', 'š' => 's', 'Š' => 'S', 'ś' => 's', 'Ś' => 'S', 'ť' => 't', 'Ť' => 'T', 'ú' => 'u', 'Ú' => 'U', 'ů' => 'u', 'Ů' => 'U', 'ü' => 'u', 'Ü' => 'U', 'ù' => 'u', 'Ù' => 'U', 'ũ' => 'u', 'Ũ' => 'U', 'û' => 'u', 'Û' => 'U', 'ý' => 'y', 'Ý' => 'Y', 'ž' => 'z', 'Ž' => 'Z', 'ź' => 'z', 'Ź' => 'Z');
    $temp = strtr($text, $array);
    return $temp;
}

$error = NULL;
$error_fatal = FALSE;

function doError($text, $fatal) {
    global $error;
    global $error_fatal;
    if ($fatal === FALSE) {
        $error .= "<span class=\"error warning\">" . ERROR_WARNING . " </span>".$text."<br />";
    } elseif (($fatal !== FALSE) && ($error_fatal == TRUE)) {
        // in case we already have one fatal error _DO_NOTHING_ to avoid stacking errors
    } else {
        $error .="<span class=\"error fatal\">" . ERROR_FATAL . " </span>".$text."<br />";
        $error_fatal = TRUE;
    }
}

function showError() {
    global $error;
    return $error;
}

function t2m($text) {
    $text = diacriticFree($text);

    if (!preg_match('!^[a-zA-Z0-9\?\,\!\.\;\/\=\-\(\)\"\:\_\@\ \n\r]+$!', $text)) {
        doError(ERROR_T2M . ERROR_TIP_LIST, true);
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
            case "a":case "A":$text[$i] = MORSE_A;
                break;
            case "b":case "B":$text[$i] = MORSE_B;
                break;
            case "c":case "C":$text[$i] = MORSE_C;
                break;
            case "d":case "D":$text[$i] = MORSE_D;
                break;
            case "e":case "E":$text[$i] = MORSE_E;
                break;
            case "f":case "F":$text[$i] = MORSE_F;
                break;
            case "g":case "G":$text[$i] = MORSE_G;
                break;
            case "h":case "H":$text[$i] = MORSE_H;
                break;
            case "ch":case "CH":$text[$i] = MORSE_CH;
                break;
            case "i":case "I":$text[$i] = MORSE_I;
                break;
            case "j":case "J":$text[$i] = MORSE_J;
                break;
            case "k":case "K":$text[$i] = MORSE_K;
                break;
            case "l":case "L":$text[$i] = MORSE_L;
                break;
            case "m":case "M":$text[$i] = MORSE_M;
                break;
            case "n":case "N":$text[$i] = MORSE_N;
                break;
            case "o":case "O":$text[$i] = MORSE_O;
                break;
            case "p":case "P":$text[$i] = MORSE_P;
                break;
            case "q":case "Q":$text[$i] = MORSE_Q;
                break;
            case "r":case "R":$text[$i] = MORSE_R;
                break;
            case "s":case "S":$text[$i] = MORSE_S;
                break;
            case "t":case "T":$text[$i] = MORSE_T;
                break;
            case "u":case "U":$text[$i] = MORSE_U;
                break;
            case "v":case "V":$text[$i] = MORSE_V;
                break;
            case "w":case "W":$text[$i] = MORSE_W;
                break;
            case "x":case "X":$text[$i] = MORSE_X;
                break;
            case "y":case "Y":$text[$i] = MORSE_Y;
                break;
            case "z":case "Z":$text[$i] = MORSE_Z;
                break;
            case " ":$text[$i] = "";
                break;
            case "0":$text[$i] = MORSE_N_0;
                break;
            case "1":$text[$i] = MORSE_N_1;
                break;
            case "2":$text[$i] = MORSE_N_2;
                break;
            case "3":$text[$i] = MORSE_N_3;
                break;
            case "4":$text[$i] = MORSE_N_4;
                break;
            case "5":$text[$i] = MORSE_N_5;
                break;
            case "6":$text[$i] = MORSE_N_6;
                break;
            case "7":$text[$i] = MORSE_N_7;
                break;
            case "8":$text[$i] = MORSE_N_8;
                break;
            case "9":$text[$i] = MORSE_N_9;
                break;
            case "?":$text[$i] = MORSE_S_QUOTATIONMARK;
                break;
            case ",":$text[$i] = MORSE_S_COMMA;
                break;
            case "!":$text[$i] = MORSE_S_EXCLAMATIONMARK;
                break;
            case ".":$text[$i] = MORSE_S_DOT;
                break;
            case ";":$text[$i] = MORSE_S_SEMICOLON;
                break;
            case "/":$text[$i] = MORSE_S_SLASH;
                break;
            case "=":$text[$i] = MORSE_S_EQUAL;
                break;
            case "-":$text[$i] = MORSE_S_DASH;
                break;
            case "  ":$text[$i] = MORSE_S_TAB;
                break;
            case "(":$text[$i] = MORSE_S_BRACKETOPEN;
                break;
            case ")":$text[$i] = MORSE_S_BRACKETCLOSE;
                break;
            case "\"":
            case "„":
            case "“": $text[$i] = MORSE_S_QUOTATIONMARK;
                break;
            case ":":$text[$i] = MORSE_S_COLON;
                break;
            case "_":$text[$i] = MORSE_S_UNDERSCORE;
                break;
            case "@":$text[$i] = MORSE_S_ATSIGN;
                break;
            default: $text[$i] == "*";
                break;
        }
    }

    $return = "";
    foreach ($text as $arr) {
        $return.= "<strong>" . $arr . "</strong> / ";
    }

    /*
      // change output from default dot/dash/slash
      $return = str_replace(".", OUTPUT_DOT, $return);
      $return = str_replace("-", OUTPUT_DASH, $return);
      $return = str_replace("/", OUTPUT_SLASH, $return);
     */
    return $return;
}

function m2t($text) {
    //strip _all_ whitespaces
    $text = str_replace(" ", "", $text);

    // change input from custom to default dot/dash/slash
    /*
      $text = str_replace(chr(149), ".", $text);
      $text = str_replace(OUTPUT_DASH, "-", $text);
      $text = str_replace(OUTPUT_SLASH, "/", $text);
     */

    if (preg_match('!^[^\.\/\-]+$!', $text) == 1) {
        doError(ERROR_M2T . ERROR_TIP_LIST, true);
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
                doError(ERROR_M2T_UNRECOGNIZED, false);
                $temp = $text[$i];
                $text[$i] = "*<span class=\"morse_error\">";
                $text[$i] .= $temp;
                $text[$i] .= "</span>*";
                unset($temp);
                break;
        }
    }

    $return = "";
    foreach ($text as $arr) {
        $return.= "<strong>" . $arr . "</strong>";
    }

    return $return;
}

function showOutput($text) {
    $text = trim($text);
    if ($text == NULL)
        doError(ERROR_EMPTY_INPUT, true);

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
