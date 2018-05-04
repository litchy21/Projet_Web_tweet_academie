<?php
require_once('Controller/Controller.php');
require_once('Model/Tweet.php');
require_once("Model/Search.php");

$tweet = new Tweet();

class HomeController extends Controller {

	public function index($tweets = [], $retweets = [], $original_tweet = NULL){
		return $this->view('home', [
			'tweets' => $tweets,
			'retweets' => $retweets,
			'original_tweet' => $original_tweet,
			'fullName' => $_SESSION['fullName'],
			'displayName' => $_SESSION['displayName'],
			'mail' => $_SESSION['mail'],
			'theme' => $_SESSION['theme'],
			'registrationDate' => $_SESSION['registrationDate'],
		]);
	}

	public function checkTweet($tweetContent){
		if (strlen($tweetContent) > 140) {
			echo "Les tweets ne doivent pas dépasser 140 caractères";
			return false;
		}else{
			return true;
		}
	}

	public function addTweet($tweet, $tweetContent, $idUser, $retweets, $original_tweet = NULL){
		if ($this->checkTweet($tweetContent)) {
			if ($tweet->addTweet($tweetContent, $idUser)) {
				$tweets = $tweet->getTweets();
				$retweets = $tweet->getReTweets();
				$this->index($tweets, $retweets, $original_tweet);
			}
		}
	}

	public function deleteTweet($idTweet, $tweet, $tweets = [], $retweets = [], $original_tweet = NULL) {
		$tweet->deleteTweet($idTweet);
		$tweets = $tweet->getTweets();
		$retweets = $tweet->getReTweets();
		$i = 0;
		foreach ($retweets as $key => $value) {
			$original_tweet[$i] = $tweet->getTweetFromReTweet($retweets[$i]['idReTweet']);
			$i++;
		}
		$this->index($tweets, $retweets, $original_tweet);
	}
}

$tweets = $tweet->getTweets();
$retweets = $tweet->getReTweets();
$i = 0;
foreach ($retweets as $key => $value) {
	$original_tweet[$i] = $tweet->getTweetFromReTweet($retweets[$i]['idReTweet']);
	$i++;
}
$home = new HomeController();

if (isset($_POST['search'])) {
	if ($_POST['search'][0] !== '@') {
		echo "La recherche doit être par pseudo valide.";
	}
	else {
		$displayN = new Search();
		$displayName = $displayN->search_member($_POST['search']);
		if ($displayName != null) {
			header("Location: index.php?controller=profile&displayName=".$displayName['displayName']);
		}else{
			echo "Cet utilisateur n'existe pas";
		}	
	}
}

if(isset($_POST['tweet'])) {
	$idUser = $_SESSION['idUser'];
	$tweetContent = $home->clean($_POST['tweet']);
	if (isset($original_tweet)) {
		$home->addTweet($tweet, $tweetContent, $idUser, $retweets, $original_tweet);
	}else{
		$home->addTweet($tweet, $tweetContent, $idUser, $retweets);
	}
}elseif (isset($_POST['retweet'])) {
	if (isset($_POST['retweetContent'])) {
		$tweet->addReTweet($_SESSION['idUser'], $_POST['retweet'], $_POST['retweetContent']);
	}else{
		$tweet->addReTweet($_SESSION['idUser'], $_POST['retweet']);
	}
	$tweets = $tweet->getTweets();
	$retweets = $tweet->getReTweets();
	$i = 0;
	foreach ($retweets as $key => $value) {
		$original_tweet[$i] = $tweet->getTweetFromReTweet($retweets[$i]['idReTweet']);
		$i++;
	}
	if (isset($original_tweet)) {
		$home->index($tweets, $retweets, $original_tweet);
	}else{
		$home->index($tweets, $retweets);
	}
}elseif(isset($_POST['delete'])){
	$i = 0;
	foreach ($retweets as $key => $value) {
		$original_tweet[$i] = $tweet->getTweetFromReTweet($retweets[$i]['idReTweet']);
		$i++;
	}
	if (isset($original_tweet)) {
		$home->deleteTweet($_POST['delete'], $tweet, $tweets, $retweets, $original_tweet);
	}else{
		$home->deleteTweet($_POST['delete'], $tweet, $tweets, $retweets);
	}
}else{
	if (isset($original_tweet)) {
		$home->index($tweets, $retweets, $original_tweet);
	}else{
		$home->index($tweets, $retweets);
	}
}




