<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;

/*
 * TODO:
 * H: Max input length?
 * H: Still better error handling - too much code and variables
 * H: Better error position show - prev. two and next two chars.
 * 
 * M: More info <div>
 * M: Better UI
 * M: Variables rename
 * 
 * L: SEO optimize? - discussion: be a script or web page/script

 * F: sentence divider?
 * F: Add check button for Ch letter -> only for slavonic languages
 * F: input only numbers - make them short? (wiki)
 * F: Add more encode/decode
 *      * base64() encode decode?
 */

require_once "include.php";

if (isset($_POST['ok']))
    $ok = 1; else
    $ok = 0;

if (isset($_POST['input']))
    $input = $_POST['input']; else
    $input = NULL;
?>
<html>
    <head>
        <LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>T2M</title>
        <script language="javascript" type="text/javascript">
            <!--
            function popup(url) {
                newwindow=window.open(url,'name','height=500,width=370px');
                if (window.focus) {newwindow.focus()}
                return false;
            }
            // -->
        </script>
    </head>
    <body>
        <div id="wrapper">
            <div id="left">
                <form action="index.php" method="POST">
                    <span class="form">Morseovka - kódování i dekódování:</span>
                    <textarea rows="6" cols="40" name="input"><?php echo $input ?></textarea>
                    <input type="submit" value="převést" name="ok" class="submit">
                </form>
            </div>

            <div id="right">
                <?php
                if ($ok == 1) {
                    $output = showOutput($input);
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
