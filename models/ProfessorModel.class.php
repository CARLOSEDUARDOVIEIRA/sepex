<?php

/**
 * Description of ProfessorModel
 *
 * @author Carlos Eduardo Vieira
 */
class ProfessorModel {

    protected function getProfessorProjeto($idprojeto, $tipo) {
        global $DB;

        $query = $DB->get_records("sepex_professor_projeto", array("idprojeto" => $idprojeto, 'tipo' => $tipo));
        $professores = array();
        foreach ($query as $professor) {
            $professores = $professor->matrprofessor;
        }
        return $professores;
    }

}
