<?php
require_once('parser.php');

$env = parse_ini_file('env.ini');

define('BOT_TOKEN', $env['token']);
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

function processMessage($message) {

    if (isset($message['text'])) {
        $parser = new Parser();
        
        $text = $message['text'];

        if (strpos($text, "/start") === 0) {
            onTimeKeyBoard($message['chat']['id'], $message['from']['first_name']);
        } else if ($text === "Mega-Sena") {
            sendMessage("sendMessage", array('chat_id' => $message['chat']['id'], "text" => $parser->getResult(New ParseMegaSena, $text)));
        } else if ($text === "Quina") {
            sendMessage("sendMessage", array('chat_id' => $message['chat']['id'], "text" => $parser->getResult(New ParseQuina, $text)));
        } else if ($text === "Lotomania") {
            sendMessage("sendMessage", array('chat_id' => $message['chat']['id'], "text" => $parser->getResult(New ParseLotoMania, $text)));
        } else if ($text === "Lotofácil") {
            sendMessage("sendMessage", array('chat_id' => $message['chat']['id'], "text" => $parser->getResult(new ParseLotoFacil, $text)));
        } else if ($text === "g1") {
            inlineKeyboardG1($message['chat']['id'], $message['from']['first_name']);
        } else {
            sendMessage("sendMessage", array('chat_id' => $message['chat']['id'], "text" => 'Desculpe, mas não entendi essa mensagem. :('));
        }
    } else {
        sendMessage("sendMessage", array('chat_id' => $message['chat']['id'], "text" => 'Desculpe, mas só compreendo mensagens em texto'));
    }

}

function sendMessage($method, $parameters) {
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'content' => json_encode($parameters),
            'header'=>  "Content-Type: application/json\r\n" .
                        "Accept: application/json\r\n"
        )
    );

    $context = stream_context_create($options);

    file_get_contents(API_URL.$method, false, $context);
}

function onTimeKeyBoard($chat_id, $from) {
    sendMessage(
        "sendMessage", array('chat_id' => $chat_id, "text" => 'Olá, '. $from.
    '! Eu sou um bot que informa o resultado do último sorteio da loteria caixa. Será que você ganhou dessa vez? Para começar, escolha qual sorteio você deseja ver o resultado',
    'reply_markup' =>
        array('keyboard' => 
            array(array('Mega-Sena', 'Quina'), array('Lotofácil', 'Lotomania')),
            'one_time_keyboard' => true
        ))
    );
}

function inlineKeyboardG1($chat_id, $from) {
    sendMessage("sendMessage", array('chat_id' => $chat_id, "text" => 'Olá, '. $from.
		'! Escolha um dos jogos abaixo para ver o resultado diretamente no site do G1.', 
        'reply_markup' => 
        array('inline_keyboard' => 
            array(
                array(
                    array('text'=>'Mega-Sena','url'=>'https://g1.globo.com/loterias/megasena.html'), //botão 1
                    array('text'=>'Quina','url'=>'https://g1.globo.com/loterias/quina.html')//botão 2
                ),

                array(
                    array('text'=>'Lotofácil','url'=>'https://g1.globo.com/loterias/lotofacil.html'), //botão 3
                    array('text'=>'Lotomania','url'=>'https://g1.globo.com/loterias/lotomania.html')//botão 4
                )
            )
        ))
    );
}

$update_response = file_get_contents("php://input");

$update = json_decode($update_response, true);

if (isset($update["message"])) {
    processMessage($update["message"]);
}
