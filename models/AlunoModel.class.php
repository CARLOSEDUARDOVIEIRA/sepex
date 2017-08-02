<?php

/**
 * Description of AlunoModel
 *
 * @author Carlos Eduardo Vieira
 */
class AlunoModel {

    protected function getAlunosProjeto($idprojeto) {
        global $DB;

        $query = $DB->get_records("sepex_aluno_projeto", array("idprojeto" => $idprojeto));
        $alunos = array();
        foreach ($query as $aluno) {
            $alunos[$aluno->idalunoprojeto] = $aluno->matraluno;
        }
        return implode(";", $alunos);
    }

}
