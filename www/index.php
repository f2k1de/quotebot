<?php
header("Content-Type: application/json");
require_once '../lib/Telegram.php';
require_once '../config.php';

global $tg;

$tg = new Telegram($api_key, $botname);

// Check if message was a command
if (strpos($tg->getText(), "/") === 0) {
	$command = strtolower(ltrim($tg->getCommand()["command"], "/"));
	switch($command) {
		case 'start':
			$tg->sendMessage('Hallo! Ich werde dir nun täglich um 12 Uhr UTC ein Zitat zusenden. Zum Austragen sende mir /stop .');
			addUser($tg->getChatID());
			break;
		case 'about':
			$tg->sendMessage('**Zitat des Tages**' . "\n"
			. 'Coded by @freddy2001' . "\n" . 'Zitate von https://taeglicheszit.at');
			break;
		case 'stop':
			removeUser($tg->getChatID());
			$tg->sendMessage('Okay! Ich werde dir ab nun keine Nachrichten mehr senden.');
			break;
	}
}

echo $tg->webhookAnswer();
$tg->answer();

function addUser($chatid) {
	$users = json_decode(file_get_contents('../users.txt'), true);
	$found = false;
	for($i = 0; $i < count($users); $i++) {
		if($users[$i]['chatid'] == $chatid) {
			$found = true;
		}
	}
	if(!$found) {
		$users[] = [
			'chatid' => $chatid,
		];
	}
	file_put_contents('../users.txt', json_encode($users));
}

function removeUser($chatid) {
	$users = json_decode(file_get_contents('../users.txt'), true);
	for($i = 0; $i < count($users); $i++) {
		if($users[$i]['chatid'] == $chatid) {
			// User is this one
			unset($users[$i]);
		}
	}
	file_put_contents('../users.txt', json_encode($users));
}