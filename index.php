﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;

/*
 * TODO:
 * H: Still better error handling - too much code and variables
 * H: Better error position show - prev. two and next two chars.
 * H: Code cleanup
 * 
 * M: Better UI
 * M: Variables rename
 * 
 * L: SEO optimize? - discussion: be a script or web page/script
 * L: javascript check for no form input
 *
 * F: sentence divider?
 * F: Add check button for Ch letter -> only for slavonic languages
 * F: input only numbers - make them short? (wiki)
 * F: Add more encode/decode
 *      * base64() encode decode?
 */

require_once "include.php";
?>

<html>
    <head>
        <LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>T2M</title>
        <script language="javascript" type="text/javascript">
            <!--
            function popup(url) {
                newwindow=window.open(url,'name','height=500,width=600px');
                if (window.focus) {newwindow.focus()}
                return false;
            }
            
            function changeVisibility(id){
                el=document.getElementById(id).style; 
                el.display=(el.display == 'block')?'none':'block';
            }
            // -->
        </script>
    </head>
    <body>
        <div id="wrapper">
            <div id="left">
                <form action="index.php" method="POST">
                    <select name="type">
                        <?php
                        if (isset($_POST['type'])) {
                            switch ($_POST['type']) {
                                case "mo":
                                    echo "<option value=\"mo\" selected>Text2Morse</option>";
                                    echo "<option value=\"bi\">Text2Binary</option>";
                                    break;
                                
                                case "bi":
                                    echo "<option value=\"mo\">Text2Morse</option>";
                                    echo "<option value=\"bi\" selected>Text2Binary</option>";
                                    break;
                                
                                default:
                                    echo "<option value=\"mo\">Text2Morse</option>";
                                    echo "<option value=\"bi\">Text2Binary</option>";
                                    break;
                            }
                        }
                        ?>
                    </select>
                    <textarea rows="6" cols="40" name="input"><?php if (isset($_POST['input']))
                            echo $_POST['input']; ?></textarea>
                    <input type="submit" value="převést" name="ok" class="submit">
                </form>
            </div>

            <div id="right">
                <?php
                if ((isset($_POST['input'])) && (isset($_POST['type']))) {
                    $output = showOutput($_POST['input'], $_POST['type']);

                    echo showError();

                    if (isError(2) != TRUE) {
                        echo $output;
                    }
                }
                ?>
            </div>
        </div>
        <div class="cleaner"></div>
        <div id="info_bubble">
            Dekódování morseovky: <i>2 možné vstupy</i><br>
            <ul type="square">
                <li><b>1) </b>písmena se oddělují mezerou, slova více mezerami</li>
                <li><b>2) </b>písmena se oddělují lomítkem, slova více lomítky (/)</li>
                <li><b>3) </b>tyto dva způsoby nelze směšovat!</li>
                <li></li>    
            </ul>
            <span style="font-size:.7em;">evandar.cz 2011, evandar *at* evandar *dot* cz, xmpp: evandar@jabber.cz</span>
        </div>
    </body>
</html>

<?php
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$totaltime = ($endtime - $starttime) * 1000;
//Uncoment this for execution time:
//echo "This page was created in " . $totaltime . " ms";
?>
