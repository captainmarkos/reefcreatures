<?php

    session_start();
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Reef Creature Quiz" />
<meta name="keywords" content="dive log, free dive log, free online diver logbook, free diver logbook" />
<title>Blue Wild - Reef Creature Quiz</title>
<link type="text/css" rel="stylesheet" href="javascript/jquery-ui-1.8.21.custom/css/custom-theme/jquery-ui-1.8.21.custom.css" />
<script type="text/javascript" src="javascript/jquery-ui-1.8.21.custom/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="javascript/jquery-ui-1.8.21.custom/js/jquery-ui-1.8.21.custom.min.js"></script>

<?php
    print "<script type=\"text/javascript\">\n\n";
    $fh = fopen("images/reefcreatures_data.csv", "r");
    if($fh) {
        print "var image = Array();\n";
        print "var question = Array();\n";
        print "var answer_a = Array();\n";
        print "var answer_b = Array();\n";
        print "var answer_c = Array();\n";
        print "var answer_d = Array();\n";
        print "var correct_answer = Array();\n";
        print "var base_url       = \"images/\";\n\n";
        $tmparray = array();

        while(($data = fgets($fh, 4096)) != false)
        {
            array_push($tmparray, $data);
        }
        shuffle($tmparray);

        $i = 0;
        $MAX_QUESTIONS = 20;  // count($tmparray);

        for($j = 0; $j < $MAX_QUESTIONS; $j++)
        {
            $row = preg_split("/,/", $tmparray[$j]);
            if(strcasecmp(trim($row[0]), "image_name") == 0) { $MAX_QUESTIONS++; continue; }
            print "image[$i]             = base_url + \"" . trim($row[0]) . "\";\n";
            print "question[$i]          = \"" . trim($row[1]) . "\";\n";
            print "answer_a[$i]          = \"" . trim($row[2]) . "\";\n";
            print "answer_b[$i]          = \"" . trim($row[3]) . "\";\n";
            print "answer_c[$i]          = \"" . trim($row[4]) . "\";\n";
            print "answer_d[$i]          = \"" . trim($row[5]) . "\";\n";
            print "correct_answer[$i]    = \"" . trim($row[6]) . "\";\n";

            print "var preload_images" . $i . "  = new Image(300, 225);\n";
	    print "preload_images" . $i . ".src  = base_url + \"" . trim($row[0]) . "\";\n\n";
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

function runQuiz()
{
    total_questions = image.length;
    if(index >= total_questions)
    {
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


function checkAnswer(user_answer, the_answer)
{
    var form = document.getElementById('myform');
    var str = "<table width=\"100%\" border=\"0\"><tr style=\"height: 28px;\">";
    if(user_answer.toLowerCase() == the_answer.toLowerCase())
    {
        // Correct answer
        // --------------
        right_answers++;
        str += "<td align=\"left\">";
        str += "<font style=\"color: #008000; font-family: Comic Sans MS, Arial, Tahoma; font-size: 18px;\">";
        str += "Correct!&nbsp;&nbsp;&nbsp;&nbsp;";
        if(index >= total_questions) { str += '<input class="rc_input" type="submit" value="Start Over" onclick="window.location.reload();" />'; }
        else                         { str += '<input class="rc_input" type="submit" value="Next" onclick="runQuiz();" />'; }
        str += "</font></td>";
        str += "<td align=\"right\">" + right_answers + " correct of " + total_questions + "</td></tr></table>";
        document.getElementById('fishquiz_answers').innerHTML = str;
    }
    else
    {
        // Wrong answer
        // ------------
        str += "<td align=\"left\">";
        str += "<font style=\"color: #cd0000; font-family: Comic Sans MS, Arial, Tahoma; font-size: 18px;\">";
        str += "Wrong&nbsp;&nbsp;&nbsp;&nbsp;";
        if(index >= total_questions) { str += '<input class="rc_input" type="submit" value="Start Over" onclick="window.location.reload();" />'; }
        else                         { str += '<input class="rc_input" type="submit" value="Next" onclick="runQuiz();" />'; }
        str += "</font></td>";
        str += "<td align=\"right\">" + right_answers + " correct of " + total_questions + "</td></tr></table>";
        document.getElementById('fishquiz_answers').innerHTML = str;

        if(the_answer.toLowerCase() == 'a') { document.getElementById('a_a').style.backgroundColor = "#ffff00"; }
        if(the_answer.toLowerCase() == 'b') { document.getElementById('a_b').style.backgroundColor = "#ffff00"; }
        if(the_answer.toLowerCase() == 'c') { document.getElementById('a_c').style.backgroundColor = "#ffff00"; }
        if(the_answer.toLowerCase() == 'd') { document.getElementById('a_d').style.backgroundColor = "#ffff00"; }
    }
    form.ans[0].disabled = true;
    form.ans[1].disabled = true;
    form.ans[2].disabled = true;
    form.ans[3].disabled = true; 
}

</script>

<link rel="stylesheet" type="text/css" href="reefcreatures.css" />
<link rel="SHORTCUT ICON" href="favicon.ico" />
<link rel="stylesheet" href="../vendor/font-awesome-4.1.0/css/font-awesome.min.css">
<link href='http://fonts.googleapis.com/css?family=Raleway:700,500,400,300,200' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="../styles/bluewild.css">
<link rel="stylesheet" href="../styles/bluewild-devices.css">
<link rel="stylesheet" href="../styles/normalize.css">

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

<!--                 -->
<!-- BEGIN: fishquiz -->
<!--                 -->

<div class="mytop">&nbsp;</div>
<div class="mycontent">
    <div style="height: 462px;">
        <center>
            <font style="color: #cd0000; font-family: Comic Sans MS, Tahoma, Arial, sans-serif; font-size: 20px;"><b>Reef Creature &amp; Fish ID Quiz</b></font>
            <br />
        </center>
        <br />
        <div id="fishquiz"></div>
        <div style="height: 34px;" id="fishquiz_answers"></div>
    </div>
</div>
<div class="mybot">&nbsp;</div>

<!--               -->
<!-- END: fishquiz -->
<!--               -->

        </div>
    </section>
</div>
</body>
</html>
