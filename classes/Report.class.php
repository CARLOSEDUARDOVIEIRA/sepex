<?php

/**
 * Cria campos personalizados para a tabela de download.
 * Documentacao: <https://docs.moodle.org/dev/lib/tablelib.php>
 * @author Carlos Eduardo Vieira
 */
require "$CFG->libdir/tablelib.php";
require "../constantes/Constantes.class.php";
require"../controllers/ProfessorController.class.php";
require "../controllers/AlunoController.class.php";
require "../controllers/ApresentacaoController.class.php";

class Report extends table_sql {

    private $id;

    function __construct($uniqueid, $id) {
        parent::__construct($uniqueid);
        $this->id = $id;
    }

    /**
     * Essa função é chamada para cada linha de dados para permitir o processamento do
     * Valor do nome de usuário.
     *
     * @param object $values Contém o objeto com todos os valores de registro.
     * @return $string return idprojeto 
     */
    function col_idprojeto($values) {

        return $values->idprojeto;
    }

    function col_codprojeto($values) {

        return $values->codprojeto;
    }

    function col_titulo($values) {

        if ($this->is_downloading()) {
            return $values->titulo;
        } else {
            return '<a href="./projetoAluno.php?id=' . $this->id . '&idprojeto=' . $values->idprojeto . '</a>';
        }
    }

    function col_resumo($values) {

        return $values->resumo;
    }

    function col_tags($values) {

        return $values->tags;
    }

    function col_dtcadastro($values) {

        return $values->dtcadastro;
    }

    function col_periodo($values) {

        return $values->idperiodo . ' Periodo';
    }

    function col_turno($values) {

        return $values->turno;
    }

    function col_areacurso($values) {
        $constantes = new Constantes();
        return $constantes->detailAreas($values->areacurso);
    }

    function col_alocamesa($values) {
        if ($values->alocamesa) {
            return 'Sim';
        }
        return 'Nao';
    }

    function col_categoria($values) {
        $constantes = new Constantes();
        return $constantes->detailCategorias($values->idprojeto);
    }

    function col_curso($values) {
        $constantes = new Constantes();
        return $constantes->detailCursos($values->idcurso);
    }

    function col_alunos($values) {
        $alunocontroller = new AlunoController();
        return implode(", ", $alunocontroller->getNameAlunos($values->idprojeto));
    }

    function col_orientador($values) {
        $professorcontroller = new ProfessorController();
        return implode(',', $professorcontroller->getNameProfessores($values->idprojeto, 'Orientador'));
    }

    function col_avaliador($values) {

        $professorcontroller = new ProfessorController();
        return implode(',', $professorcontroller->getNameProfessores($values->idprojeto, 'Avaliador'));
    }

    function col_nomelocalapresentacao($values) {
        $apresentacaocontroller = new ApresentacaoController();
        return $apresentacaocontroller->detailApresentacao($values->idprojeto)->nomelocalapresentacao;
    }

    function col_dtapresentacao($values) {
        $apresentacaocontroller = new ApresentacaoController();
        $date = $apresentacaocontroller->detailApresentacao($values->idprojeto)->dtapresentacao;
        if ($date) {
            return date("d/m/Y H:i:s", $date);
        }
        return 'Nao definido';
    }

    function col_notafinal($values) {
        if ($values->notafinal) {
            return ($values->notafinal / 4);
        }
    }

}
