<html>
    <head>        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style>
            td {border: 1px solid black;}
            #left {float:left;}
            #right{float:left;margin-left:50px;}
            #right td {text-align:center;}
        </style>
    </head>
    <body>
        <?php
        require_once "define.php";

        $i = 1;
        echo "<div id=\"left\"><table>";

        switch ($_GET['type']) {
            case "type_mo":
                $temp = $morse;
                break;

            case "type_bi":
                $temp = $binary;
                break;

            default:
                die("Unhandled."); //TODO: UNHANDLED
                break;
        }

        foreach ($temp as $key => $value) {
            echo "\n\t\t<tr><td>" . $key . "</td><td>" . $value . "</td></tr>";
            if (($i % 25) == 0) {
                echo "\n\t</table>\n\t</div>\n\n<div id=\"right\">\n<table>";
            }
            $i++;
        }

        echo "</div>";
        ?>
    </table>
</body>
</html>