<?php

/**
 * Relatorio de avaliacao personalizado
 *
 * @author Carlos Eduardo Vieira
 */
require "../constantes/Constantes.class.php";

class ReportAvaliacao extends table_sql {

    private $id;

    function __construct($uniqueid, $id) {
        parent::__construct($uniqueid);
        $this->id = $id;
    }

    function col_titulo($values) {
	if (!$this->is_downloading()) {
        return '<a href="./definicaoProjeto.php?id=' . $this->id . '&idprojeto=' . $values->idprojeto . '&area=' . $values->areacurso . '&turno=' . $values->turno . '&idcategoria=' . $values->idcategoria . '">' . $values->titulo . '</a>';
	}else{
		return $values->titulo;
	}
    }

    function col_nomeprofessor($values) {
        return $values->nomeprofessor;
    }

    function col_notafinal($values) {
        if ($values->notafinal) {
            return $values->notafinal / 2;
        } else {
            $values->notafinal;
        }
    }

    function col_area($values) {
        $const = new Constantes();
        return $const->detailAreas($values->areacurso);
    }

    function col_curso($values) {
        $const = new Constantes();
        return $const->detailCursos($values->idcurso);
    }

}
