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

    protected function getProjetosProfessor($professor) {
        global $DB;

        return $DB->get_records_sql("
            SELECT
            sp.idprojeto,
            sp.titulo,
            sp.idcategoria,
            sp.idcurso,
            sp.statusresumo,
            spp.tipo,
            SUM(sap.totalresumo + sap.totalavaliacao) notafinal
            FROM mdl_sepex_professor_projeto spp 
            INNER JOIN mdl_sepex_projeto sp ON sp.idprojeto = spp.idprojeto
            LEFT JOIN mdl_sepex_avaliacao_projeto sap ON spp.idprofessorprojeto = sap.idprofessorprojeto
            WHERE spp.matrprofessor = ?
            GROUP BY sp.idprojeto, sp.titulo, sp.idcategoria, sp.idcurso, sp.statusresumo, spp.tipo ORDER BY spp.tipo"
                        , array($professor));
    }
    
}
