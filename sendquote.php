<?php
require_once 'lib/Telegram.php';
require_once 'config.php';

class SendQuote {
	private $tg;
	function __construct($api_key, $botname) {
		$this->tg = new Telegram($api_key, $botname);
		$quote = $this->getQuote();
		$users = $this->getUsers();
		$this->sendChat($quote, $users);
		$this->tg->answer();
	}

	private function getQuote() {
		$quote = json_decode(file_get_contents('http://taeglicheszit.at/zitat-api.php?format=json'), true);
		$text = $quote['zitat'] . "\n—" . $quote['autor'];
		return $text;
	}

	private function getUsers() {
		return json_decode(file_get_contents('users.txt'), true);
	}

	private function sendChat($quote, $users) {
		for($i = 0; $i < count($users); $i++) {
			$this->tg->sendMessage($quote, $users[$i]['chatid']);
		}
	}
}

new SendQuote($api_key, $botname);