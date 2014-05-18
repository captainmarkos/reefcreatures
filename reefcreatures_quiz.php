<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>divefortlauderdale.com - Fish ID Quiz</title>
<link rel="stylesheet" type="text/css" href="reefcreatures.css" />
<link rel="SHORTCUT ICON" href="favicon.ico" />

<?php
    print "<script type=\"text/javascript\">\n\n";
    $fh = fopen("images/reefcreatures_data.csv", "r");
    if($fh)
    {
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
?>

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
</head>
<body onload="runQuiz();">
<center>
<table border="2" class="border" style="border-color: #0000cd;"  width="793" cellpadding="0" cellspacing="0">
<tr>
<td>
    <table border="0" bgcolor="#ffffff" width="100%" cellpadding="6" cellspacing="0">

    <tr>
        <td valign="top">
        <!--                       -->
        <!-- BEGIN : Main Content  -->
        <!--                       -->

<center>
    <h3><font color="#000080"><b>Test Your Knowledge</b></font></h3>
</center>


        <table width="90%" border="0">
            <tr>
                <td align="left" valign="top">
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

<!--                 -->
<!-- END: fishquiz   -->
<!--                 -->
                </td>
                <td style="text-align: justify; padding-top: 8px;" valign="top" class="rc_maintext">
SCUBA diving and snorkeling is similar to going for a walk in the woods. 
You never know what you are going to see!
<br />
<br />
The fish and other reef creatures you will see in this identification quiz are local here to the South Florida diving area.  Most of the pictures were taken while diving in Fort Lauderdale however some were also taken in the Florida Keys.  Pictures were taken on the many reefs and wrecks throughout the area.  The dives were also a combination of shore dives and boat dives.
<br />
<br />
Test your knowledge and see how many reef creatures you can identify.  Good luck!
<br />
<br />
<br />

                </td>
            </tr>
        </table>
        <br />
        <br />
        <br />
        <br />
        
        </td>
    </tr>

    <tr>
        <td valign="top">

        <br />

        </td>
    </tr>
    </table>
</td>
</tr>
</table>
</center>

</body>
</html>
