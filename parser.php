<?php
define('BASE_URL',"http://g1.globo.com/loterias/");
define('URL_MEGA', BASE_URL.'megasena.html');
define('URL_QUINA', BASE_URL.'quina.html');
define('URL_LOTOMANIA', BASE_URL.'lotomania.html');
define('URL_LOTOCACIL', BASE_URL.'lotofacil.html');

function getResult($lottery, $title){

	$out = "Resultado: ".$title;

	if($lottery=="megasena"){
		$out .= parser(URL_MEGA);
	}elseif($lottery=="quina"){
		$out .= parser(URL_QUINA);
	}elseif($lottery=="lotofacil"){
		$out .= parser(URL_LOTOMANIA);
	}else{
		$out .= parser(URL_LOTOCACIL);
	}

	return $out;
}

function parser($url){
	$html = file_get_contents($url);
	if (!empty($html)) {
		preg_match('#<span class="content-lottery__info">(.*?)\s*-\s*(\d+/\d+/\d+)\s*</span>#is', $html, $concurso_header);
		
		$concurso = str_replace('CONCURSO', 'Concurso:', $concurso_header[1]);
		$data     = $concurso_header[2];

		preg_match('#<div class="content-lottery__ammount">(.*?)</div>#is', $html, $concurso_acumulado);

		$acumulado = "Não Acumulou!";
		if($concurso_acumulado[1]) {
			$acumulado = str_replace('ACUMULADO:', '', trim($concurso_acumulado[1]));
		}

		preg_match_all('#content-lottery__result--megasena">(.*?)</div>#is', $html, $concurso_numeros);
		
		$numeros_sorteados = !empty($concurso_numeros[1]) ? implode($concurso_numeros[1]) : 'Não consegui identificar os números.';
		
		preg_match('#lottery__table-content">(.*?)</table>#is', $html, $concurso_premios);
		
		$premios = '';

		if(!empty($concurso_premios[1])) {
			preg_match_all('#<td class="col-acertos">(.*?)</td>[^>]*?>(.*?)</td>[^>]*?>(.*?)</td>#is', $concurso_premios[1], $resultado);

			foreach($resultado[1] as $key => $valor) {
				if(trim($resultado[2][$key]) == 'Acumulou!'){
					$premios .= trim($valor).': '.$resultado[2][$key]."\n";
				} else {
					$premios .= trim($valor).': '. $resultado[2][$key] . ' pessoas ganharam ' . $resultado[3][$key] ."\n";
				}
			}
		}

		return "\n<br>---------------".
		"\n<br>".$concurso .
		"\n<br>Data: " . $data .
		"\n<br>Números:  " . $numeros_sorteados .
		"\n<br>Acumulado: ".$acumulado.
		"\n<br>---------------".
		"\n<br>Premiação:".
		"\n<br>---------------\n<br>".$premios.
		"---------------";
	}else{
		return "\nNão encontrado";
	}
}


// echo getResult('megasena', 'Mega sena');