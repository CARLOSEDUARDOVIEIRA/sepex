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

    protected function getNameAlunos($idprojeto) {
        global $DB;
        $nomealunos =  $DB->get_records_sql("
        SELECT
            u.username,
            CONCAT(u.firstname,' ',u.lastname) as name            
            FROM mdl_sepex_aluno_projeto sap                        
            INNER JOIN mdl_user u ON u.username = sap.matraluno            
            WHERE sap.idprojeto = {$idprojeto}");
        $alunos = array();    
        foreach($nomealunos as $nomes){
            array_push($alunos, $nomes->name);
        }    
        return implode(", ", $alunos);
    }

}
