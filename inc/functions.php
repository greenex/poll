<?php
require ("db.php");
//GETTING VARIABLES START
if (isset($_POST['action'])) {
	$action = mysql_real_escape_string($_POST['action']);
}
if (isset($_POST['pollAnswerID'])) {
	$pollAnswerID = mysql_real_escape_string($_POST['pollAnswerID']);
}
//GETTING VARIABLES END

function getPoll($pollID) {
	$query = "SELECT * FROM polls LEFT JOIN pollanswers ON polls.pollID = pollanswers.pollID WHERE polls.pollID = " . $pollID . ";";
	$result = mysql_query($query);
	//echo $query;jquery

	$pollStartHtml = '';
	$pollAnswersHtml = '';
	if (isset($_COOKIE["poll".$pollID])) {
		$response = getPollResults($pollID);
		// $response;
		$splittedResponse = explode("-", $response);
		$numberOfAnswers = count($splittedResponse) - 2;
		$pollAnswerTotalPoints = $splittedResponse[$numberOfAnswers + 1];
		$count = 0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pollQuestion = $row['pollQuestion'];
			$pollAnswerID = $row['pollAnswerID'];
			$pollAnswerValue = $row['pollAnswerValue'];
			$pollId = $row['pollID'];
			$splittedAnswer = explode("|", $splittedResponse[$count]);
			
			$pollAnswerID = $splittedAnswer[0];
			$pollAnswerPoints = $splittedAnswer[1];
			$pollAnswerColor = $splittedAnswer[2];
			//echo $pollAnswerColor;
			$pollPercentage = (100 * $pollAnswerPoints / $pollAnswerTotalPoints);

			if ($pollStartHtml == '') {
				$pollStartHtml = '<div id="pollWrap"><form name="pollForm" method="post" action=""><h3>' . $pollQuestion . '</h3><ul>';
				$pollEndHtml = '</ul><input type="button" name="pollSubmit_' . $pollId . '" onClick="javascript: answer(' . $pollId . ');" id="pollSubmit_' . $pollId . '" value="Vote" /> <span id="pollMessage_' . $pollId . '"></span><img src="ajaxLoader.gif" alt="Ajax Loader" class="pollAjaxLoader" id="pollAjaxLoader_' . $pollId . '" /></form></div>';
			}
			$pollAnswersHtml = $pollAnswersHtml . '<li><input name="pollAnswerID_' . $pollId . '" id="pollRadioButton' . $pollAnswerID . '" type="radio" value="' . $pollAnswerID . '" /> ' . $pollAnswerValue . '<span style="width:' . $pollPercentage . '%;" id="pollAnswer' . $pollAnswerID . '"> (' .round($pollPercentage) ."% - " . $pollAnswerPoints . " votes)".'</span></li>';
			$pollAnswersHtml = $pollAnswersHtml . '<li style="background-color:' . $pollAnswerColor . '; width:'.$pollPercentage.'%;"  class="pollChart pollChart' . $pollAnswerID . '"></li>';
		$count++;
}

	} else {

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$pollQuestion = $row['pollQuestion'];
			$pollAnswerID = $row['pollAnswerID'];
			$pollAnswerValue = $row['pollAnswerValue'];
			$pollId = $row['pollID'];

			if ($pollStartHtml == '') {
				$pollStartHtml = '<div id="pollWrap"><form name="pollForm" method="post" action=""><h3>' . $pollQuestion . '</h3><ul>';
				$pollEndHtml = '</ul><input type="button" name="pollSubmit_' . $pollId . '" onClick="javascript: answer(' . $pollId . ');" id="pollSubmit_' . $pollId . '" value="Vote" /> <span id="pollMessage_' . $pollId . '"></span><img src="ajaxLoader.gif" alt="Ajax Loader" class="pollAjaxLoader" id="pollAjaxLoader_' . $pollId . '" /></form></div>';
			}
			$pollAnswersHtml = $pollAnswersHtml . '<li><input name="pollAnswerID_' . $pollId . '" id="pollRadioButton' . $pollAnswerID . '" type="radio" value="' . $pollAnswerID . '" /> ' . $pollAnswerValue . '<span id="pollAnswer' . $pollAnswerID . '"></span></li>';
			$pollAnswersHtml = $pollAnswersHtml . '<li class="pollChart pollChart' . $pollAnswerID . '"></li>';
		}

	}
	echo $pollStartHtml . $pollAnswersHtml . $pollEndHtml;

}

function getPollID($pollAnswerID) {
	$query = "SELECT pollID FROM pollanswers WHERE pollAnswerID = " . $pollAnswerID . " LIMIT 1";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);

	return $row['pollID'];
}

function getPollResults($pollID) {

	$colorArray = array(1 => "#ffcc00", "#00ff00", "#cc0000", "#0066cc", "#ff0099", "#ffcc00", "#00ff00", "#cc0000", "#0066cc", "#ff0099");
	$colorCounter = 1;
	$query = "SELECT pollAnswerID, pollAnswerPoints FROM pollanswers WHERE pollID = " . $pollID . ";";
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		if ($pollResults == "") {
			$pollResults = $row['pollAnswerID'] . "|" . $row['pollAnswerPoints'] . "|" . $colorArray[$colorCounter];
		} else {
			$pollResults = $pollResults . "-" . $row['pollAnswerID'] . "|" . $row['pollAnswerPoints'] . "|" . $colorArray[$colorCounter];
		}
		$colorCounter = $colorCounter + 1;
	}
	$query = "SELECT SUM(pollAnswerPoints) FROM pollanswers WHERE pollID = " . $pollID . "";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$pollResults = $pollResults . "-" . $row['SUM(pollAnswerPoints)'];
	return $pollResults;
}

//VOTE START
if ($action == "vote") {
	if (isset($_COOKIE["poll" . getPollID($pollAnswerID)])) {
		echo "voted";
	} else {
		$query = "UPDATE pollanswers SET pollAnswerPoints = pollAnswerPoints + 1 WHERE pollAnswerID = " . $pollAnswerID . "";
		mysql_query($query) or die('Error, insert query failed');
		setcookie("poll" . getPollID($pollAnswerID), "1", time() + 259200,"/");
		//setcookie("poll" . getPollID($pollAnswerID), 1, time()+259200, "/", ".webresourcesdepot.com");
		echo getPollResults(getPollID($pollAnswerID));
	}
}
//VOTE END

if (mysql_real_escape_string($_GET['cleanCookie']) == 1) {
	setcookie("poll1", "", time() - 3600, "/");
	//header('Location: http://webresourcesdepot.com/wp-content/uploads/file/ajax-poll-script/');
}
?>
