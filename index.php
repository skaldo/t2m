<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
require_once "config.php";

if (($config['show_gen_time']) == TRUE) {
    $mtime = microtime();
    $mtime = explode(" ", $mtime);
    $mtime = $mtime[1] + $mtime[0];
    $starttime = $mtime;
}

/*
 * TODO:
 * H: Still better error handling - too much code and variables
 * H: Better error position show - prev. two and next two chars.
 *      - error position for Binary
 * H: Code cleanup
 * H: Locales - make everything into defines or something like this. Now we have some of them in defs, others are hardcoded
 * 
 * M: Better UI
 * M: Copy to clipboard
 * 
 * F: sentence divider?
 * F: input only numbers - make them short? (wiki)
 * F: Add more encode/decode
 *      * base64() encode decode?
 * F: API
 */

require_once "include.php";

checkEverything();
// Load everything we want to output into $output
if ((isset($_POST['input'])) && (isError(2) != TRUE))
    $output['text'] = showOutput($_POST['input'], $_POST['type']);
$output['errors'] = showError();
?>

<html>
    <head>
        <LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>T2M</title>
        <script language="javascript" type="text/javascript">
            function popup(url) {
                newwindow=window.open(url,'name','height=500,width=600px');
                if (window.focus) {newwindow.focus()}
                return false;
            }
            
            function changeVisibility(id){
                el=document.getElementById(id).style; 
                el.display=(el.display == 'block')?'none':'block';
            }
            
        </script>
    </head>
    <body>
        <div id="wrapper">
            <div id="left">
                <form action="index.php" method="POST">
                    <select name="type">
                        <option value="mo"<?php if ((isset($_POST['type']))&&($_POST['type']=="mo")) echo " selected"; ?>>Text2Morse</option>
                        <option value="bi"<?php if ((isset($_POST['type']))&&($_POST['type']=="bi")) echo " selected"; ?>>Text2Binary</option>
                    </select>

                    <textarea rows="6" cols="40" name="input"><?php if (isset($_POST['input']))
                            echo $_POST['input']; ?></textarea>

                    <input type="submit" value="převést" name="ok" class="submit">
                </form>

                <?php
                if ((isset($_POST['type'])) && ($_POST['input'] != NULL)) {
                    echo "<div id=\"options\">";

                    switch ($_POST['type']) {
                        case 'mo':
                            echo "Placeholder";
                            break;

                        case 'bi':
                            echo "<a href=\"#\" onclick=\"smazMezeryDiv();\">Vystup bez mezer</a>";
                            break;

                        default:
                            break;
                    }
                    echo "</div>";
                }
                ?>

                <div id="info">
                    Contact me by <b>twitter:</b> @evandar or by <b>xmpp:</b> evandar@jabber.cz
                </div>

            </div>

            <div id="right">
                <?php
                echo $output['errors'];
                if (isset($output['text']))
                    echo $output['text'];
                ?>
            </div>

        </div>
        <div class="cleaner"></div>

    </body>

    <script language="javascript" type="text/javascript">
        /* smazMezeryDiv */
        var element = document.getElementById("right");
        var string = element.innerHTML;
        var spacesFree = string.split(' ').join('');
        function smazMezeryDiv(vrat) {
                
            if (right.innerHTML == spacesFree) {
                right.innerHTML = string;
            }
 
            else if (right.innerHTML == string) {
                right.innerHTML = spacesFree;
            }
        }</script>

</html>

<?php
if (($config['show_gen_time']) == TRUE) {
    $mtime = microtime();
    $mtime = explode(" ", $mtime);
    $mtime = $mtime[1] + $mtime[0];
    $endtime = $mtime;
    $totaltime = ($endtime - $starttime) * 1000;
    echo "This page was generated in " . $totaltime . " ms";
}
?>
