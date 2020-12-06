<?php
require_once('parse-interface.php');
require_once('parse-mega.php');
require_once('parse-quina.php');
require_once('parse-lotofacil.php');
require_once('parse-lotomania.php');


class Parser
{
	public function getResult(Bot $lottery, $title)
	{
		$out = "Resultado: ".$title;

		$out .= $lottery->crawler();
	
		return $out;
	}
}
