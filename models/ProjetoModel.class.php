<?php

require_once '../classes/Projeto.class.php';
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');

/**
 * Description of ProjetoModel
 *
 * @author Carlos Eduardo Vieira
 */
class ProjetoModel extends Projeto {

    protected function save($dados) {
        global $USER, $DB;
        $date = new DateTime("now", core_date::get_user_timezone_object());
        $dados->data_cadastro = userdate($date->getTimestamp());
        $dados->cod_projeto = $this->createCodigoProjeto($dados->cod_categoria, $dados->cod_curso);
        $dados->area_curso = $this->createAreaCurso($dados->cod_curso);
        $dados->email = 'dullvieira'; //$USER->email;
        $projeto = parent::__construct($dados);

        $insert = (object) array('data_cadastro' => $projeto->data_cadastro,
                    'cod_projeto' => $projeto->cod_projeto,
                    'titulo' => $projeto->titulo,
                    'resumo' => $projeto->resumo,
                    'email' => $projeto->email,
                    'tags' => $projeto->tags,
                    'periodo' => $projeto->periodo,
                    'turno' => $projeto->turno,
                    'area_curso' => $projeto->area_curso,
                    'mesa' => $projeto->mesa,
                    'cod_categoria' => $projeto->cod_categoria
        );
        return $DB->insert_record('sepex_projeto', $insert, $returnid = true);

        $curso = new stdClass();
        $curso->curso_cod_curso = $dados->cod_curso;
        $curso->projeto_id_projeto = $id;
        $DB->insert_record("sepex_projeto_curso", $curso);
    }

    /** Metodo responsavel por criar o codigo do projeto
     * @global type $DB
     * @param type $cod_categoria
     * @param type $cod_curso
     * @return string
     */
    private function createCodigoProjeto($cod_categoria, $cod_curso) {
        global $DB;
        $numero = $DB->count_records('sepex_projeto', array('cod_categoria' => $cod_categoria));
        $categoria = [
            "1" => "EGR",
            "2" => "EST",
            "3" => "INC",
            "4" => "INO",
            "5" => "PE",
            "6" => "PI",
            "7" => "RS",
            "8" => "TL",
            "9" => "TCC",
        ];
        if ($numero == null):
            $numero = 0;
        endif;

        $codigo = 'SEP17' . $cod_curso . $categoria[$cod_categoria] . '0' . $numero;

        return $codigo;
    }

    private function createAreaCurso($cod_curso) {

        if ($cod_curso == 'ADM' || $cod_curso == 'AUR' || $cod_curso == 'CONT' || $cod_curso == 'TDI' || $cod_curso == 'DIR' || $cod_curso == 'FIL' || $cod_curso == 'PIS' || $cod_curso == 'SES' || $cod_curso == 'EDF'):
            return 1;

        elseif ($cod_curso == 'ENP' || $cod_curso == 'ENC' || $cod_curso == 'SIN' || $cod_curso == 'TADS' || $cod_curso == 'TLO' || $cod_curso == 'RED'):
            return 2;

        elseif ($cod_curso == 'CBB' || $cod_curso == 'CBL' || $cod_curso == 'ENF' || $cod_curso == 'FTP' || $cod_curso == 'NUT' || $cod_curso == 'FAR'):
            return 3;
        endif;
    }

}
