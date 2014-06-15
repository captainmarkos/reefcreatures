<?php

    session_start();
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Reef Creature Quiz - See how well you know your fish and other reef creatures in the Atlantic / Caribbean area." />
<meta name="keywords" content="free atlantic caribbean reef creatures quiz" />
<meta name="author" content="Captain Markos" />

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
var question_index = 0;
var right_answers = 0;
var total_questions = 0;

function runQuiz() {
    total_questions = image.length;
    if(question_index >= total_questions) {
        right_answers = 0; 
        question_index = 0; 
    }

    var score  = '<div id="main-rc-score">';
        score += '<div id="correct-rc-score">' + right_answers + ' correct of '+ total_questions + '</div>';

    var i = question_index;

    var str  = '<img id="rc-img" src="' + image[i] + '" />';
        str += '<b>' + (question_index +1) + '.</b>&nbsp;&nbsp;' + question[i] + '<br /><br />';

        str += '<div id="ans_A">';
        str += '<input class="rc-radio" type="radio" value="a" name="ans" id="rc-radio-ans-a" ';
        str += '    onclick="checkAnswer(\'a\', \'' + correct_answer[i] + '\');" />';
        str += '<div class="rc-letter">A.</div><div id="a_a" class="rc-answer-text">' + answer_a[i] + '</div>';
        str += '</div>';

        str += '<div id="ans_B">';
        str += '<input class="rc-radio" type="radio" value="b" name="ans" id="rc-radio-ans-b" ';
        str += '    onclick="checkAnswer(\'b\', \'' + correct_answer[i] + '\');" />';
        str += '<div class="rc-letter">B.</div><div id="a_b" class="rc-answer-text">' + answer_b[i] + '</div>';
        str += '</div>';

        str += '<div id="ans_C">';
        str += '<input class="rc-radio" type="radio" value="c" name="ans" id="rc-radio-ans-c" ';
        str += '    onclick="checkAnswer(\'c\', \'' + correct_answer[i] + '\');" />';
        str += '<div class="rc-letter">C.</div><div id="a_c" class="rc-answer-text">' + answer_c[i] + '</div>';
        str += '</div>';

        str += '<div id="ans_D">';
        str += '<input class="rc-radio" type="radio" value="d" name="ans" id="rc-radio-ans-d" ';
        str += '    onclick="checkAnswer(\'d\', \'' + correct_answer[i] + '\');" />';
        str += '<div class="rc-letter">D.</div><div id="a_d" class="rc-answer-text">' + answer_d[i] + '</div>';
        str += '</div>';

    document.getElementById('fishquiz').innerHTML = str;

    var ans_A = document.getElementById('ans_A');
    var ans_B = document.getElementById('ans_B');
    var ans_C = document.getElementById('ans_C');
    var ans_D = document.getElementById('ans_D');
    if(ans_A) ans_A.onclick = function() { checkAnswer('a', correct_answer[i]); };
    if(ans_B) ans_B.onclick = function() { checkAnswer('b', correct_answer[i]); };
    if(ans_C) ans_C.onclick = function() { checkAnswer('c', correct_answer[i]); };
    if(ans_D) ans_D.onclick = function() { checkAnswer('d', correct_answer[i]); };

    document.getElementById('rc-quiz-answers').innerHTML = score;

    question_index++;
}

function checkAnswer(user_answer, the_answer) {
    var ans_A = document.getElementById('rc-radio-ans-a');
    var ans_B = document.getElementById('rc-radio-ans-b');
    var ans_C = document.getElementById('rc-radio-ans-c');
    var ans_D = document.getElementById('rc-radio-ans-d');

    var str = '<table width="100%" id="rc-answer-table"><tr>';
    if(user_answer.toLowerCase() == the_answer.toLowerCase()) {
        // Correct answer
        // --------------
        right_answers++;
        str += '<td align="left">';
        str += 'Correct!&nbsp;&nbsp;&nbsp;&nbsp;';
        if(question_index >= total_questions) { str += '<input class="rc-input" type="submit" value="Start Over" onclick="window.location.reload();" />'; }
        else                         { str += '<input class="rc-input" type="submit" value="Next" onclick="runQuiz();" />'; }
        str += '</td>';
        str += '<td align="right">' + right_answers + ' correct of ' + total_questions + '</td></tr></table>';
        document.getElementById('rc-quiz-answers').innerHTML = str;

        if(the_answer.toLowerCase() == 'a') { ans_A.checked = true; document.getElementById('a_a').className = 'rc-answer-correct'; }
        if(the_answer.toLowerCase() == 'b') { ans_B.checked = true; document.getElementById('a_b').className = 'rc-answer-correct'; }
        if(the_answer.toLowerCase() == 'c') { ans_C.checked = true; document.getElementById('a_c').className = 'rc-answer-correct'; }
        if(the_answer.toLowerCase() == 'd') { ans_D.checked = true; document.getElementById('a_d').className = 'rc-answer-correct'; }
    }
    else {
        // Wrong answer
        // ------------
        str += '<td align="left">';
        str += 'Wrong&nbsp;&nbsp;&nbsp;&nbsp;';
        if(question_index >= total_questions) { str += '<input class="rc-input" type="submit" value="Start Over" onclick="window.location.reload();" />'; }
        else                         { str += '<input class="rc-input" type="submit" value="Next" onclick="runQuiz();" />'; }
        str += '</td>';
        str += '<td align="right">' + right_answers + ' correct of ' + total_questions + '</td></tr></table>';
        document.getElementById('rc-quiz-answers').innerHTML = str;

        if(the_answer.toLowerCase() == 'a') { ans_A.checked = true; document.getElementById('a_a').className = 'rc-answer-wrong'; }
        if(the_answer.toLowerCase() == 'b') { ans_B.checked = true; document.getElementById('a_b').className = 'rc-answer-wrong'; }
        if(the_answer.toLowerCase() == 'c') { ans_C.checked = true; document.getElementById('a_c').className = 'rc-answer-wrong'; }
        if(the_answer.toLowerCase() == 'd') { ans_D.checked = true; document.getElementById('a_d').className = 'rc-answer-wrong'; }
    }

    var answer = document.getElementsByClassName('rc-radio');
    if(answer) {
        answers_not_clickable();
    }
}

function answers_not_clickable() {
    try {
        document.getElementById('ans_A').style.cursor = 'default';
        document.getElementById('ans_B').style.cursor = 'default';
        document.getElementById('ans_C').style.cursor = 'default';
        document.getElementById('ans_D').style.cursor = 'default';

        // Need to clear the onclick handlers.
        document.getElementById('ans_A').onclick = function() { console.log('--> ans_A'); return true; };
        document.getElementById('ans_B').onclick = function() { console.log('--> ans_B'); return true; };
        document.getElementById('ans_C').onclick = function() { console.log('--> ans_C'); return true; };
        document.getElementById('ans_D').onclick = function() { console.log('--> ans_D'); return true; };
    } catch(e) {
        console.log('--> Exception caught:');
        console.log(e);
    }
}
</script>

<link type="text/css" rel="stylesheet" href="reefcreatures.css" />
<link type="text/css" rel="stylesheet" href="../vendor/font-awesome-4.1.0/css/font-awesome.min.css" />
<link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Raleway:400" />
<link type="text/css" rel="stylesheet" href="../styles/foundation.css" />
<link type="text/css" rel="stylesheet" href="../styles/bluewild.css" />
<link type="text/css" rel="stylesheet" href="../styles/normalize.css" />

<script type="text/javascript">

    var email = '<?php echo $email; ?>';

</script>
<title>Blue Wild - Reef Creature Quiz</title>
</head>
<body onload="runQuiz();">

<header>
   <div class="row offset-top">
      <div class="small-12 medium-6 large-6 columns no-padding small-only-text-center">
         <h3>dive the blue wild</h3>
      </div>
      <div class="small-12 medium-6 large-6 columns contact-info small-only-text-center">
         <a href="tel:19542135067"><i class="fa fa-phone"></i> : (954) 213-5067</a>&nbsp;&nbsp;
         <br class="show-for-small-only" />
         <br class="show-for-small-only" />
         <a href="mailto:bluewildscuba@gmail.com" target="_blank">
         <i class="fa fa-envelope"></i> : bluewildscuba@gmail.com</a>
      </div>
   </div>
</header>

<!-- navbar -->
<div class="row">
    <div class="large-12 column nav">
        <ul class="inline-list">
          <li class="no-margin-left"><a href="/"><i class="fa fa-home icon-font-size"></i></a></li>
          <li><a href="/courses">Scuba Courses</a></li>
          <li><a href="/aboutus">About Us</a></li>
          <li><a href="/divelog/index.php" class="hide-for-small-only">Dive Log</a></li>
          <li><a href="/reefcreatures/index.php">Reef Creature Quiz</a></li>
        </ul>
    </div>
</div>

<div class="row panel-margin">
    <div class="large-12 columns no-padding">
        <div class="panel">
            <!--single row for info-->
            <div class="panel">
                <div class="row">
                    <div class="large-12 columns">
                        <h4>Reef Creature &amp; Fish ID Quiz</h4>
                        <div id="fishquiz"></div>
                        <div id="rc-quiz-answers"></div>
                    </div>
                </div>
            </div>
            <!--end content-->
        </div>
    </div>
</div>

</body>
</html>
