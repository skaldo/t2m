<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
/*
 * TODO:
 * H: Better error handling! if one fatal, dont make another, just end quietly.
 *       - move "chyba" and "upozorneni" into defines for easier change.
 * H: Determine what to do with custom output or try to make it work.
 * H: Max $_POST length?
 * 
 * M: Better UI
 * 
 * L: SEO optimize? - discussion: be a script or web page/script
 * 
 * F: oddělovač vět?
 * F: Add more encode/decode
 *      * if so, rewrite m2t and t2m. Rename to morseCode (morse()?) and add 
 *          parameter - encode=BOOL (ok?) => morseCode("hello world",true);
 *      * ruzne sifry s posunem slov
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
                    <span class="form">Input:</span>
                    <textarea rows="6" cols="40" name="input"><?php echo $input ?></textarea>
                    <input type="submit" value="převést" name="ok" class="submit">
                </form>
            </div>

            <div id="right">
                <?php
                if ($ok == 1) {
                    //do this before errors, we want to have errors on top without using CSS position:abs/rel
                    //put output into $output, echo later
                    $output = showOutput($input);

                    //is there an error?
                    if (showError() != NULL) {
                        echo "<div id=\"error\">" . showError() . "</div>";
                    }

                    //echo output here
                    if ($error_fatal != TRUE)
                        echo "<div id=\"output\">" . $output . "</div>";
                }
                ?>
            </div>
        </div>
    </div>
    <div class="cleaner"></div>
    <div id="info_bubble">
        Dekódování morseovky
        <ul type="circle">
            <li>Písmena se oddělují lomítkem <b>/</b> , mezeru zastupují lomítka dvě <b>//</b></li>
        </ul>
        <span style="font-size:.7em;">evandar.cz 2011, evandar *at* evandar *dot* cz, xmpp: evandar@jabber.cz</span>
    </div>
</body>
</html>
