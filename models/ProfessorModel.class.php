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
            array_push($professores, $professor->matrprofessor);
        }

        return $professores;
    }

    protected function saveAvaliacaoOrientador($avaliacao, $idprojeto, $matrprofessor) {
        global $DB;

        $date = new DateTime("now", core_date::get_user_timezone_object());
        $dataAtual = userdate($date->getTimestamp());

        return $DB->execute("
        UPDATE mdl_sepex_projeto sp
        INNER JOIN mdl_sepex_professor_projeto spp
        ON sp.idprojeto = spp.idprojeto
        SET sp.resumo = ?,
        sp.tags = ?,
        sp.statusresumo = ?,        
        sp.obsorientador = ?,
        spp.dtavaliacao = ?
        WHERE sp.idprojeto = {$idprojeto} AND matrprofessor = {$matrprofessor} AND tipo = 'Orientador' ", array($avaliacao->resumo[text], $avaliacao->tags, $avaliacao->statusresumo, $avaliacao->obsorientador, $dataAtual));
    }

    protected function getNameProfessores($idprojeto, $tipo) {
        global $DB;
        $nomeprofessores = $DB->get_records_sql("
        SELECT
            u.username,
            CONCAT(u.firstname,' ',u.lastname) as name            
            FROM mdl_sepex_professor_projeto spp                        
            INNER JOIN mdl_user u ON u.username = spp.matrprofessor            
            WHERE spp.idprojeto = {$idprojeto} AND spp.tipo = '{$tipo}'");
        $professores = array();
        foreach ($nomeprofessores as $nomes) {
            $professores[$nomes->username] = $nomes->name;
        }
        return $professores;
    }

}
