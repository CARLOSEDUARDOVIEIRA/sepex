<?php

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');

/**
 * Description of ProjetoModel
 *
 * @author Carlos Eduardo Vieira
 */
class ProjetoModel {

    private $idprojeto;
    private $areacurso;
    private $idcategoria;
    private $codprojeto;
    private $dtcadastro;
    private $email;
    private $alocamesa;
    private $idperiodo;
    private $resumo;
    private $statusresumo;
    private $obsorientador;
    private $tags;
    private $turno;
    private $titulo;
    private $idcurso;

    /** metodo que irah salvar um novo projeto no bd, <b>Optei por nao utilizar 
     * transactions pelo fato de o moodle da ucv estar desatualizado
     * e estudando o forum de desenvolvedores moodle, percebi que estavam com 
     * duvidas sobre a eficacia dessa funcionalidade no framework.
     * <https://moodle.org/mod/forum/discuss.php?d=135847>, de qualquer forma em 
     * caso de necessidade de acordo com a evolucao do evento sepex isso pode 
     * ser implementado.</b>
     */
    protected function save($projeto) {
        global $USER, $DB;

        $date = new DateTime("now", core_date::get_user_timezone_object());
        $projeto->dtcadastro = userdate($date->getTimestamp());
        $projeto->codprojeto = $this->createCodigoProjeto($projeto->idcategoria);
        $projeto->email = 'dullvieira'; //$USER->email;
        $curso = $DB->get_record('sepex_curso', array('idcurso' => $projeto->idcurso));
        $projeto->areacurso = $curso->areacurso;

        $idprojeto = $DB->insert_record('sepex_projeto', $projeto, $returnid = true);

        $orientador = (object) array('idprojeto' => $idprojeto,
                    'matrprofessor' => $projeto->matrprofessor,
                    'tipo' => 'Orientador'
        );

        $DB->insert_record("sepex_professor_projeto", $orientador);
        $this->saveAluno($idprojeto, $projeto->matraluno);
    }

    protected function update($projeto) {
        global $USER, $DB;

        $date = new DateTime("now", core_date::get_user_timezone_object());
        $projeto->dtcadastro = userdate($date->getTimestamp());

        $projetoantigo = $DB->get_record("sepex_projeto", array('idprojeto' => $projeto->idprojeto));

        if ($projetoantigo->idcategoria != $projeto->idcategoria) {
            $projeto->codprojeto = $this->createCodigoProjeto($projeto->idcategoria);
        } else {
            $projeto->codprojeto = $projetoantigo->codprojeto;
        }
        $curso = $DB->get_record('sepex_curso', array('idcurso' => $projeto->idcurso));
        $projeto->areacurso = $curso->areacurso;
        $projeto->email = 'dullvieira'; //$USER->email;

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
                WHERE sp.idprojeto = {$projeto->idprojeto}", array($projeto->areacurso,
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
            $projeto->matrprofessor
                )
        );

        //Estudarei como melhorar este update dos alunos.
        $response = $DB->delete_records('sepex_aluno_projeto', array("idprojeto" => $projeto->idprojeto));
        if ($response) {
            $this->saveAluno($projeto->idprojeto, $projeto->matraluno);
        }
    }

    protected function delete($idprojeto) {
        global $DB;

        $avaliadores = $DB->get_records('sepex_professor_projeto', array('idprojeto' => $idprojeto, 'tipo' => 'avaliador'));
        if ($avaliadores) {
            foreach ($avaliadores as $avaliacao) {
                $DB->delete_records('sepex_avaliacao_projeto', array("idprofessorprojeto" => $avaliacao->idprofessorprojeto));
            }
        }
        $DB->delete_records('sepex_aluno_projeto', array("idprojeto" => $idprojeto));
        $DB->delete_records('sepex_professor_projeto', array("idprojeto" => $idprojeto));
        $DB->delete_records('sepex_projeto', array("idprojeto" => $idprojeto));
        $DB->delete_records('sepex_definicao_projeto', array("idprojeto" => $idprojeto));
    }
    
    protected function detail($idprojeto) {
        global $DB;
        $projetodetalhado = new stdClass();
        $projetodetalhado->professores = $DB->get_records('sepex_professor_projeto', array('idprojeto' => $idprojeto));
        $projetodetalhado->avaliacao = array();
        if ($projetodetalhado->professores) {
            foreach ($projetodetalhado->professores as $avaliacao) {
                array_push($projetodetalhado->avaliacao, $DB->get_records('sepex_avaliacao_projeto', array("idprofessorprojeto" => $avaliacao->idprofessorprojeto)));
            }
        }
        $projetodetalhado->alunos = $DB->get_records('sepex_aluno_projeto', array("idprojeto" => $idprojeto));
        $projetodetalhado->projeto = $DB->get_records('sepex_projeto', array("idprojeto" => $idprojeto));
        $projetodetalhado->definicao = $DB->get_records('sepex_definicao_projeto', array("idprojeto" => $idprojeto));
        return $projetodetalhado;
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

    private function saveAluno($idprojeto, $student) {
        global $USER, $DB;
        $alunocadastrante = (object) array('matraluno' => $student, //$USER->username;,
                    'idprojeto' => $idprojeto
        );
        $DB->insert_record("sepex_aluno_projeto", $alunocadastrante);

        $demaisalunos = explode(";", $student);
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

}
