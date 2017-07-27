<?php

require_once '../classes/Projeto.class.php';
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');

/**
 * Description of ProjetoModel
 *
 * @author Carlos Eduardo Vieira
 */
class ProjetoModel extends Projeto {

    /** metodo que irah salvar um novo projeto no bd, <b>Optei por nao utilizar 
     * transactions pelo fato de o moodle da ucv estar desatualizado
     * e estudando o forum de desenvolvedores moodle, percebi que estavam com 
     * duvidas sobre a eficacia dessa funcionalidade no framework.
     * <https://moodle.org/mod/forum/discuss.php?d=135847>, de qualquer forma em 
     * caso de necessidade de acordo com a evolucao do evento sepex isso pode 
     * ser implementado.</b>
     */
    protected function save($dados) {
        global $USER, $DB;
        $date = new DateTime("now", core_date::get_user_timezone_object());
        $dados->dtcadastro = userdate($date->getTimestamp());
        $dados->codprojeto = $this->createCodigoProjeto($dados->idcategoria);
        $dados->areacurso = $this->createAreaCurso($dados->idcurso);
        $dados->email = 'dullvieira'; //$USER->email;
        $projeto = parent::validation($dados);

        $insert = (object) array('areacurso' => $projeto->areacurso,
                    'idcategoria' => $projeto->idcategoria,
                    'codprojeto' => $projeto->codprojeto,
                    'dtcadastro' => $projeto->dtcadastro,
                    'email' => $projeto->email,
                    'alocamesa' => $projeto->alocamesa,
                    'idperiodo' => $projeto->idperiodo,
                    'resumo' => $projeto->resumo,
                    'statusresumo' => $projeto->statusresumo,
                    'obsorientador' => $projeto->obsorientador,
                    'tags' => $projeto->tags,
                    'turno' => $projeto->turno,
                    'titulo' => $projeto->titulo,
                    'idcurso' => $projeto->idcurso
        );
        $idprojeto = $DB->insert_record('sepex_projeto', $insert, $returnid = true);

        $orientador = (object) array('idprojeto' => $idprojeto,
                    'matrprofessor' => $dados->matrprofessor,
                    'tipo' => 'Orientador'
        );

        $DB->insert_record("sepex_professor_projeto", $orientador);
        $alunocadastrante = (object) array('matraluno' => $dados->matraluno, //$USER->username;,
                    'idprojeto' => $idprojeto,
        );
        $DB->insert_record("sepex_aluno_projeto", $alunocadastrante);

        $demaisalunos = explode(";", $dados->matraluno);
        if (count($demaisalunos) > 1) {
            foreach ($demaisalunos as $aluno) {
                $matraluno = trim($aluno, " \t,"); //remove espacos e retira uma possivel , da ultima matricula informada.
                $alunovalido = is_numeric($matraluno) && strlen($matraluno) == 10 && $matraluno != $USER->username;
                if ($alunovalido) {
                    $aluno = (object) array('matraluno' => $alunovalido,
                                'idprojeto' => $idprojeto
                    );
                    $DB->insert_record("sepex_aluno_projeto", $aluno);
                }
            }
        }
    }

    protected function update($dados) {
        global $USER, $DB;

        $date = new DateTime("now", core_date::get_user_timezone_object());
        $dados->dtcadastro = userdate($date->getTimestamp());

        $projetoantigo = $DB->get_record("sepex_projeto", array('idprojeto' => $dados->idprojeto));

        if ($projetoantigo->idcategoria != $dados->idcategoria) {
            $dados->codprojeto = $this->createCodigoProjeto($dados->idcategoria);
        } else {
            $dados->codprojeto = $projetoantigo->codprojeto;
        }
        $dados->areacurso = 1; //$this->createAreaCurso($dados->idcurso);
        $dados->email = 'dullvieira'; //$USER->email;

        $projeto = parent::validation($dados);

        $DB->execute("
            UPDATE mdl_sepex_projeto sp
            INNER JOIN mdl_sepex_professor_projeto spp
            ON sp.idprojeto = spp.idprojeto
                SET sp.areacurso = ?,
                sp.idcategoria = ?, 
                sp.codprojeto = ?,
                sp.dtcadastro = ?,                
                sp.email = ?,
                sp.alocamesa = ?,
                sp.idperiodo = ?,
                sp.resumo = ?,
                sp.statusresumo = ?,
                sp.obsorientador = ?,
                sp.tags = ?,
                sp.turno = ?,
                sp.titulo = ?,
                sp.idcurso = ?,
                spp.matrprofessor = ?
                WHERE sp.idprojeto = {$dados->idprojeto}", array($projeto->areacurso,
            $projeto->idcategoria,
            $projeto->codprojeto,
            $projeto->dtcadastro,
            $projeto->email,
            $projeto->alocamesa,
            $projeto->idperiodo,
            $projeto->resumo,
            $projeto->statusresumo,
            $projeto->obsorientador,
            $projeto->tags,
            $projeto->turno,
            $projeto->titulo,
            $projeto->idcurso,
            $dados->matrprofessor
                )
        );

        return $projeto->codprojeto;
//
//        $orientador = (object) array('idprojeto' => $idprojeto,
//                    'matrprofessor' => $dados->matrprofessor,
//                    'tipo' => 'Orientador'
//        );
//
//        $DB->insert_record("sepex_professor_projeto", $orientador);
//        $alunocadastrante = (object) array('matraluno' => $dados->matraluno, //$USER->username;,
//                    'idprojeto' => $idprojeto,
//        );
//        $DB->insert_record("sepex_aluno_projeto", $alunocadastrante);
//
//        $demaisalunos = explode(";", $dados->matraluno);
//        if (count($demaisalunos) > 1) {
//            foreach ($demaisalunos as $aluno) {
//                $matraluno = trim($aluno, " \t,"); //remove espacos e retira uma possivel , da ultima matricula informada.
//                $alunovalido = is_numeric($matraluno) && strlen($matraluno) == 10 && $matraluno != $USER->username;
//                if ($alunovalido) {
//                    $aluno = (object) array('matraluno' => $alunovalido,
//                                'idprojeto' => $idprojeto
//                    );
//                    $DB->insert_record("sepex_aluno_projeto", $aluno);
//                }
//            }
//        }
    }

    private function createCodigoProjeto($idcategoria) {
        global $DB;
        $numero = $DB->count_records('sepex_projeto', array('idcategoria' => $idcategoria));
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

        $codigo = 'SEP17' . $categoria[$idcategoria] . $numero;

        return $codigo;
    }

    private function createAreaCurso($cod_curso) {

        if ($cod_curso == 'ADM' || $cod_curso == 'AUR' ||
                $cod_curso == 'CONT' || $cod_curso == 'TDI' ||
                $cod_curso == 'DIR' || $cod_curso == 'FIL' ||
                $cod_curso == 'PIS' || $cod_curso == 'SES' || $cod_curso == 'EDF') {
            return 1;
        } elseif ($cod_curso == 'ENP' || $cod_curso == 'ENC' ||
                $cod_curso == 'SIN' || $cod_curso == 'TADS' ||
                $cod_curso == 'TLO' || $cod_curso == 'RED') {
            return 2;
        } elseif ($cod_curso == 'CBB' || $cod_curso == 'CBL' ||
                $cod_curso == 'ENF' || $cod_curso == 'FTP' ||
                $cod_curso == 'NUT' || $cod_curso == 'FAR') {
            return 3;
        }
    }

}
