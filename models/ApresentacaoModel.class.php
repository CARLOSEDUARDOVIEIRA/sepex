<?php

/**
 * Description of ApresentacaoModel
 *
 * @author Carlos Eduardo Vieira
 */
class ApresentacaoModel {

    protected function detailApresentacao($idprojeto) {
        global $DB;

        return $DB->get_records_sql("
        SELECT    
            sdp.idprojeto,
            sla.nomelocalapresentacao,
            sdp.dtapresentacao,
            sdp.idlocalapresentacao
            FROM mdl_sepex_definicao_projeto sdp
            INNER JOIN mdl_sepex_local_apresentacao sla ON sla.idlocalapresentacao = sdp.idlocalapresentacao    
            WHERE sdp.idprojeto = {$idprojeto}")[$idprojeto];
    }

    protected function detailProjetosFiltrados($filtro) {
        global $DB;

        return $DB->get_records_sql("
            SELECT            
            sp.idprojeto,            
            sp.titulo,
            sp.idperiodo
            FROM mdl_sepex_projeto sp
            WHERE sp.areacurso = ? AND sp.turno = ? AND sp.idcategoria = ? AND sp.statusresumo = 1
            ORDER BY sp.idperiodo, sp.idcurso
            ", array($filtro->areacurso, $filtro->turno, $filtro->idcategoria));
    }

    protected function save($definicao) {
        global $DB;

        $DB->insert_record("sepex_definicao_projeto", $definicao);
        $this->saveProfessorAvaliador($definicao);
    }

    protected function update($definicao) {
        global $DB;
    
        $DB->execute("
        UPDATE mdl_sepex_definicao_projeto                                        
            SET dtapresentacao = ?,                 
            idlocalapresentacao = ?                 
            WHERE idprojeto = {$definicao->idprojeto}", array(
            $definicao->dtapresentacao,
            $definicao->idlocalapresentacao)
        );

        $DB->delete_records('sepex_professor_projeto', array('idprojeto' => $definicao->idprojeto, 'tipo' => 'Avaliador'));
        $this->saveProfessorAvaliador($definicao);
    }

    private function saveProfessorAvaliador($definicao) {
        global $DB;

        $professor = new stdClass();
        $professor->idprojeto = $definicao->idprojeto;
        $professor->matrprofessor = $definicao->avaliador;
        $professor->tipo = 'Avaliador';
        $DB->insert_record("sepex_professor_projeto", $professor);
        if (!empty($definicao->avaliador2)) {
            $professor->matrprofessor = $definicao->avaliador2;
            $DB->insert_record("sepex_professor_projeto", $professor);
        }
    }

}
