<?php

    session_start();
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Reef Creature Quiz" />
<meta name="keywords" content="free creatures quiz" />
<title>Blue Wild - Reef Creature Quiz</title>

<?php
    // NOTE: Here we read in the CSV data file and shuffle the fish quiz records.
    // Then output some javascript that will preload 20 ramdomly selected creature
    // images.
    echo "<script type=\"text/javascript\">\n\n";
    $fh = fopen("images/reef_data/reefcreatures_data.csv", "r");
    if($fh) {
        echo "var image = Array();\n";
        echo "var question = Array();\n";
        echo "var answer_a = Array();\n";
        echo "var answer_b = Array();\n";
        echo "var answer_c = Array();\n";
        echo "var answer_d = Array();\n";
        echo "var correct_answer = Array();\n";
        echo "var base_url       = 'images/reef_images/';" . "\n\n";
        $tmparray = array();

        while(($data = fgets($fh, 4096)) != false) {
            array_push($tmparray, $data);
        }
        shuffle($tmparray);

        $i = 0;
        $MAX_QUESTIONS = 20;

        for($j = 0; $j < $MAX_QUESTIONS; $j++) {
            $row = preg_split('/,/', $tmparray[$j]);
            if(strcasecmp(trim($row[0]), 'image_name') == 0) { $MAX_QUESTIONS++; continue; }
            echo "image[$i]             = base_url + '" . trim($row[0]) . "';\n";
            echo "question[$i]          = '" . trim($row[1]) . "';\n";
            echo "answer_a[$i]          = '" . trim($row[2]) . "';\n";
            echo "answer_b[$i]          = '" . trim($row[3]) . "';\n";
            echo "answer_c[$i]          = '" . trim($row[4]) . "';\n";
            echo "answer_d[$i]          = '" . trim($row[5]) . "';\n";
            echo "correct_answer[$i]    = '" . trim($row[6]) . "';\n";

            echo "var preload_images" . $i . "  = new Image(300, 225);\n";
	    echo "preload_images" . $i . ".src  = base_url + '" . trim($row[0]) . "';\n\n";
            $i++;
	}
    }
    fclose($fh);
    echo '</script>';

?>

<script type="text/javascript">
var index = 0;
var right_answers = 0;
var total_questions = 0;

function runQuiz() {
    total_questions = image.length;
    if(index >= total_questions) {
        right_answers = 0; 
        index = 0; 
    }

    var score  = '<div id="main-rc-score">';
        score += '<div id="correct-rc-score">' + right_answers + ' correct of '+ total_questions + '</div>';

    var i = index;

    var str  = '<center>';
        str += '<img src="' + image[i] + '" width="300" height="225" border="1" />';
        str += '</center><br />';
        str += '<b>' + (index +1) + '.</b>&nbsp;&nbsp;' + question[i] + '<br /><br />';

        str += '<input class="rc_radio" type="radio" value="a" name="ans" id="ans" ';
        str += "onclick=\"checkAnswer('a', '" + correct_answer[i] + "');\" />";
        str += "&nbsp;A)&nbsp; <span id=\"a_a\">" + answer_a[i] + "</span><br />";

        str += "<input class=\"rc_radio\" type=\"radio\" value=\"b\" name=\"ans\" id=\"ans\" onclick=\"checkAnswer('b', '" + correct_answer[i] + "');\" />";
        str += "&nbsp;B)&nbsp; <span id=\"a_b\">" + answer_b[i] + "</span><br />";

        str += "<input class=\"rc_radio\" type=\"radio\" value=\"c\" name=\"ans\" id=\"ans\" onclick=\"checkAnswer('c', '" + correct_answer[i] + "');\" />";
        str += "&nbsp;C)&nbsp; <span id=\"a_c\">" + answer_c[i] + "</span><br />";

        str += "<input class=\"rc_radio\" type=\"radio\" value=\"d\" name=\"ans\" id=\"ans\" onclick=\"checkAnswer('d', '" + correct_answer[i] + "');\" />";
        str += "&nbsp;D)&nbsp; <span id=\"a_d\">" + answer_d[i] + "</span><br />";

        str += '<br />';

    document.getElementById('fishquiz').innerHTML = str;
    document.getElementById('fishquiz_answers').innerHTML = score;

    index++;
}

function runQuiz2() {
    total_questions = image.length;
    if(index >= total_questions) {
        right_answers = 0; 
        index = 0; 
    }

    var score = "<table width=\"100%\" border=\"0\"><tr style=\"height: 28px;\">";
        score += "<td>&nbsp;</td><td align=\"right\">" + right_answers + " correct of " + total_questions + "</td>";
        score += "</tr></table>";

    var i = index;

    var str = "<form action=\"#\" method=\"post\" id=\"myform\" name=\"myform\">";
        str += "<center>";
        str += "<img src=\"" + image[i] + "\" width=\"300\" height=\"225\" border=\"1\" />";
        str += "</center><br />";
        str += "<font style=\"color: #000000; font-family: Arial, Tahoma, Verdana; font-size: 14px;\">";
        str += "<b>" + (index +1) + ".</b>&nbsp;&nbsp;" + question[i] + "<br /><br />";

        str += "<input class=\"rc_radio\" type=\"radio\" value=\"a\" name=\"ans\" id=\"ans\" onclick=\"checkAnswer('a', '" + correct_answer[i] + "');\" />";
        str += "&nbsp;A)&nbsp; <span id=\"a_a\">" + answer_a[i] + "</span><br />";

        str += "<input class=\"rc_radio\" type=\"radio\" value=\"b\" name=\"ans\" id=\"ans\" onclick=\"checkAnswer('b', '" + correct_answer[i] + "');\" />";
        str += "&nbsp;B)&nbsp; <span id=\"a_b\">" + answer_b[i] + "</span><br />";

        str += "<input class=\"rc_radio\" type=\"radio\" value=\"c\" name=\"ans\" id=\"ans\" onclick=\"checkAnswer('c', '" + correct_answer[i] + "');\" />";
        str += "&nbsp;C)&nbsp; <span id=\"a_c\">" + answer_c[i] + "</span><br />";

        str += "<input class=\"rc_radio\" type=\"radio\" value=\"d\" name=\"ans\" id=\"ans\" onclick=\"checkAnswer('d', '" + correct_answer[i] + "');\" />";
        str += "&nbsp;D)&nbsp; <span id=\"a_d\">" + answer_d[i] + "</span><br />";

        str += "</font><br />";
        str += "</form>";

    document.getElementById('fishquiz').innerHTML = str;
    document.getElementById('fishquiz_answers').innerHTML = score;

    index++;
}

function checkAnswer(user_answer, the_answer) {
  //var form = document.getElementById('myform');
    var str = '<table width="100%" border="0"><tr style="height: 28px;">';
    if(user_answer.toLowerCase() == the_answer.toLowerCase()) {
        // Correct answer
        // --------------
        right_answers++;
        str += '<td align="left">';
        str += '<font style="color: #008000; font-family: Comic Sans MS, Arial, Tahoma; font-size: 18px;">';
        str += 'Correct!&nbsp;&nbsp;&nbsp;&nbsp;';
        if(index >= total_questions) { str += '<input class="rc_input" type="submit" value="Start Over" onclick="window.location.reload();" />'; }
        else                         { str += '<input class="rc_input" type="submit" value="Next" onclick="runQuiz();" />'; }
        str += '</font></td>';
        str += '<td align="right">' + right_answers + ' correct of ' + total_questions + '</td></tr></table>';
        document.getElementById('fishquiz_answers').innerHTML = str;
    }
    else {
        // Wrong answer
        // ------------
        str += '<td align="left">';
        str += '<font style="color: #cd0000; font-family: Comic Sans MS, Arial, Tahoma; font-size: 18px;">';
        str += 'Wrong&nbsp;&nbsp;&nbsp;&nbsp;';
        if(index >= total_questions) { str += '<input class="rc_input" type="submit" value="Start Over" onclick="window.location.reload();" />'; }
        else                         { str += '<input class="rc_input" type="submit" value="Next" onclick="runQuiz();" />'; }
        str += '</font></td>';
        str += "<td align=\"right\">" + right_answers + " correct of " + total_questions + "</td></tr></table>";
        document.getElementById('fishquiz_answers').innerHTML = str;

        if(the_answer.toLowerCase() == 'a') { document.getElementById('a_a').style.backgroundColor = "#00ff00"; }
        if(the_answer.toLowerCase() == 'b') { document.getElementById('a_b').style.backgroundColor = "#00ff00"; }
        if(the_answer.toLowerCase() == 'c') { document.getElementById('a_c').style.backgroundColor = "#00ff00"; }
        if(the_answer.toLowerCase() == 'd') { document.getElementById('a_d').style.backgroundColor = "#00ff00"; }
    }

    var answer = document.getElementsByClassName('rc_radio');
    if(answer) {
        answer[0].disabled = true;
        answer[1].disabled = true;
        answer[2].disabled = true;
        answer[3].disabled = true;
    }
}

</script>

<link type="text/css" rel="stylesheet" type="text/css" href="reefcreatures.css" />
<link type="text/css" rel="stylesheet" href="../vendor/font-awesome-4.1.0/css/font-awesome.min.css" />
<link type="text/css" href="http://fonts.googleapis.com/css?family=Raleway:700,500,400,300,200" rel="stylesheet" />
<link type="text/css" rel="stylesheet" href="../styles/bluewild.css" />
<link type="text/css" rel="stylesheet" href="../styles/bluewild-devices.css" />
<link type="text/css" rel="stylesheet" href="../styles/normalize.css" />

<script type="text/javascript">

    var email = '<?php echo $email; ?>';

</script>
</head>
<body onload="runQuiz();">

<header>
    <div class="header-wrapper">
      <h1 id="logo">dive the blue wild</h1>
      <div class="contact-info">
         <a href="tel:19542135067">
            <i class="fa fa-phone"></i> : (954) 213-5067&nbsp;&nbsp;
         </a>
         <br class="rw-break" /> <a href="mailto:bluewildscuba@gmail.com" target="_blank">
            <i class="fa fa-envelope"></i> : bluewildscuba@gmail.com
         </a>
      </div>
    </div>
</header>

<div class="main-wrapper">
    <nav>
      <ul>
        <li><a href="../"><i class="fa fa-home icon-font-size"></i></a></li>
        <li><a href="../#/courses">Scuba Courses</a></li>
        <li><a href="../#/aboutus">About Us</a></li>
        <li><a href="../divelog/index.php">Dive Log</a></li>
        <li><a class="selected" href="reefcreatures/index.php">Reef Creature Quiz</a></li>
      </ul>
    </nav>

    <section>
        <div class="main-content">
            <div class="row no-margin">
                <div class="row-desc">
                    <h4>Reef Creature &amp; Fish ID Quiz</h4>
                    <div id="fishquiz"></div>
                    <div id="fishquiz_answers"></div>
                </div>
            </div>
        </div>
    </section>
</div>
</body>
</html>
