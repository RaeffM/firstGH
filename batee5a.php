<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            Query: <br>
            <br>
            <input type="text" name="name"><br>
            <input type="submit">
        </form>
        <?php
        $freq = array(array());
        $tf = array(array());
        $idf = array();
        $df = array();
        $weight = array(array());
        $sim = array();
        $sqsum = array();
        $cosim = array();
        $QErr = "";
        $flag = 1;
        $SC = array(array());
        $SC[0] = "Doc1";
        $SC[1] = "Doc2";
        $SC[2] = "Doc3";


        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $d[0] = "";
            $d[0] = $query = $_POST["name"];

            if (empty($_POST["name"])) {

                $QErr = "Query is required";
                echo $QErr;
            } else {
                $dd1 = fopen("D1.txt", "r");
                $d[1] = fread($dd1, filesize("D1.txt"));
                $dd2 = fopen("D2.txt", "r");
                $d[2] = fread($dd2, filesize("D2.txt"));
                $dd3 = fopen("D3.txt", "r");
                $d[3] = fread($dd3, filesize("D3.txt"));
                $All = implode(" ", $d);
                $All = preg_split("/[\s]+/", trim($All));
                $query = preg_split("/[\s]+/", trim($query));
                $query = array_unique($query);
                $cou = 0;
                foreach ($query as $la2) {
                    $query1[$cou] = $la2;
                    $cou++;
                }
                $norm = array_unique($All);
                $c = 0;
                $ARR = array();
                foreach ($norm as $la2a) {
                    $ARR[$c] = $la2a;
                    $freq[$c][0] = $la2a;
                    $c++;
                }
                print_r($ARR);
                for ($i = 0; $i < count($d); $i++) {
                    for ($j = 0; $j < count($ARR); $j++) {
                        $freq[$j][$i] = substr_count($d[$i], $ARR[$j]);
                    }
                }
                for ($i = 0; $i < count($d); $i++) {
                    for ($j = 0; $j < count($ARR); $j++) {
                        $tf[$j][$i] = $freq[$j][$i] / max(array_column($freq, $i));
                    }
                }
                for ($i = 0; $i < count($ARR); $i++) {
                    $df[$i] = 0;
                    for ($j = 0; $j < count($d); $j++) {
                        if ($freq[$i][$j] != 0) {
                            $df[$i]++;
                        }
                        else
                            continue;
                    }
                }
                for ($i = 0; $i < count($df); $i++) {
                    if ($df[$i] == 0) {
                        $idf[$i] = 0;
                    } else {
                        $idf[$i] = log(count($d) / $df[$i], 2);
                    }
                }
                for ($i = 0; $i < count($d); $i++) {
                    for ($j = 0; $j < count($ARR); $j++) {
                        $weight[$j][$i] = $tf[$j][$i] * $idf[$j];
                    }
                }
                for ($i = 0; $i < count($d)-1; $i++) {
                    for ($j = 0; $j < count($ARR); $j++)
                        {
                        $sim[$i] = $weight[$j][0] * $weight[$j][$i+1];
                    }
                }
                for ($i = 0; $i < count($d); $i++) {
                    $sqsum[$i] = 0;
                    for ($j = 0; $j < count($ARR); $j++) {
                        $sqsum[$i] = $sqsum[$i] + pow($weight[$j][$i], 2);
                    }
                    
                }
                for ($i = 0 , $l=1; $i < count($d) - 1;$l++, $i++) {
                        $cosim[$i] = $sim[$i] / sqrt($sqsum[0]) * sqrt($sqsum[$i+1]);
                        $cosim[$i]."Doc $l";
                    }
                    rsort($cosim);
                 for ($i = 0; $i <= count($cosim) ; $i++) {
                        echo"$cosim[$i]";
                    }
                    
                    
            }
        }
            ?>
    </body>
</html>