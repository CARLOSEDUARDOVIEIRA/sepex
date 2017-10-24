<?php

/**
 * Description of ReportPresentes
 *
 * @author salesiano
 */
require '../constantes/Constantes.class.php';

class ReportPresentes extends table_sql {

    private $id;

    function __construct($uniqueid, $id) {
        parent::__construct($uniqueid);
        $this->id = $id;
    }

    function col_codprojeto($values) {
        return $values->codprojeto;
    }

    function col_titulo($values) {
        return $values->titulo;
    }

    function col_curso($values) {
        $const = new Constantes();
        return $const->detailCursos($values->idcurso);
    }

    function col_periodo($values) {

        return $values->idperiodo . ' Periodo';
    }

    function col_turno($values) {

        return $values->turno;
    }

    function col_alunos($values) {
        return $values->nomealuno;
    }

    function col_presenca($values) {
        return $values->presenca == 0 ? "Faltoso" : "Presente";
    }

}
