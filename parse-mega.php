<?php
define('URL_MEGA',"http://g1.globo.com/loterias/megasena.html");

class ParseMegaSena implements Bot
{
    private $url;

    public function __construct($url = URL_MEGA)
    {
        $this->url = $url;
    }

    public function crawler() {
        $html = file_get_contents($this->url);
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
    
            return "\n---------------".
            "\n".trim($concurso) .
            "\nData: " . $data .
            "\nNúmeros:  " . $numeros_sorteados .
            "\nAcumulado: ".$acumulado.
            "\n---------------".
            "\nPremiação:".
            "\n---------------\n".$premios.
            "---------------";
        }else{
            return "\nNão encontrado";
        }
    }
    
}
