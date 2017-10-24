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
        $nomealunos = $DB->get_records_sql("
        SELECT
            u.username,
            CONCAT(u.firstname,' ',u.lastname) as name            
            FROM mdl_sepex_aluno_projeto sap                        
            INNER JOIN mdl_user u ON u.username = sap.matraluno            
            WHERE sap.idprojeto = {$idprojeto}");
        $alunos = array();
        foreach ($nomealunos as $nomes) {
            $alunos[$nomes->username] = $nomes->name;
        }
        return $alunos;
    }

    protected function getPresencaAluno($idprojeto, $matraluno) {
        global $DB;

        return $DB->get_records_sql("
        SELECT
            idprojeto,
            matraluno,
            presenca
            FROM mdl_sepex_aluno_projeto                                    
            WHERE idprojeto = {$idprojeto} AND matraluno = {$matraluno}");
    }

    protected function getNotaFinalProjetoAluno($idprojeto) {
        global $DB;

        return $DB->get_records_sql("
        SELECT
            spp.idprofessorprojeto, (sap.totalresumo + sap.totalavaliacao) notafinal
            FROM mdl_sepex_professor_projeto spp
            LEFT JOIN mdl_sepex_avaliacao_projeto sap ON spp.idprofessorprojeto = sap.idprofessorprojeto
            WHERE spp.idprojeto = {$idprojeto}");
    }

    protected function getLocalApresentacaoAluno($matraluno) {
        global $DB;

        return $DB->get_records_sql("
        SELECT DISTINCT sap.idprojeto, CONCAT(u.firstname,' ',u.lastname) as name, sdp.dtapresentacao, slp.nomelocalapresentacao 
            FROM mdl_sepex_aluno_projeto sap
            INNER JOIN mdl_sepex_definicao_projeto sdp ON sap.idprojeto = sdp.idprojeto
            INNER JOIN mdl_sepex_local_apresentacao slp ON  slp.idlocalapresentacao = sdp.idlocalapresentacao
            INNER JOIN mdl_user u ON u.username = sap.matraluno
            WHERE sap.matraluno = {$matraluno}");
    }

}
