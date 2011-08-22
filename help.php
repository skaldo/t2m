<html>
    <head>        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style>
            td {border: 1px solid black;}
            #left {float:left;}
            #right{float:left;margin-left:50px;}
            td {text-align:center;}
        </style>
    </head>
    <body>
        <div id="left">
            <table>
                <?php
                require_once "define.php";

                $i = 1;

                switch ($_GET['type']) {
                    case "mo":
                        $temp = $morse;
                        break;

                    case "bi":
                        $temp = $binary;
                        break;

                    default:
                        die("Unhandled."); //TODO: UNHANDLED
                        break;
                }

                foreach ($temp as $key => $value) {
                    //HACK show "space" instead of " "
                    if (($temp == $binary ) && ($value == "00100000" )) {
                        echo "\n\t\t<tr><td> SPACE </td><td>" . $value . "</td></tr>";
                    } elseif (($temp == $morse) && ($value == "")) {
                        //HACK - show nothing for array(" " => "")
                    } else {
                        echo "\n\t\t<tr><td>" . $key . "</td><td>" . $value . "</td></tr>";
                    }

                    if (($i % 25) == 0) {
                        echo "\n</table>\n</div>\n\n<div id=\"right\">\n<table>";
                    }
                    $i++;
                }
                ?>
            </table>
        </div>
    </body>
</html>