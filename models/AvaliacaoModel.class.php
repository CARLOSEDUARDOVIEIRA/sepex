<?php

/** AvaliacaoModel
 * @author Carlos Eduardo Vieira
 */
class AvaliacaoModel {

    function save($idprojeto, $notas) {
        global $DB, $USER;

        $notas->idprofessorprojeto = $DB->get_records('sepex_professor_projeto', array('matrprofessor' => $USER->username, 'idprojeto' => $idprojeto), null, 'idprofessorprojeto')[1]->idprofessorprojeto;
        $DB->insert_record("sepex_avaliacao_projeto", $notas);
    }

    function atualizar_avaliacao_avaliador($dados, $id) {
        global $DB;

        $total_resumo = $dados->resumo1 + $dados->resumo2 + $dados->resumo3 + $dados->resumo4 + $dados->resumo5;
        $total_avaliacao = $dados->apresentacao1 + $dados->apresentacao2 + $dados->apresentacao3 + $dados->apresentacao4 + $dados->apresentacao5 + $dados->apresentacao6;
        if ($dados->apresentacao1 == '') {
            $dados->apresentacao1 = NULL;
        }
        if ($dados->apresentacao2 == '') {
            $dados->apresentacao2 = NULL;
        }
        if ($dados->apresentacao3 == '') {
            $dados->apresentacao3 = NULL;
        }
        if ($dados->apresentacao4 == '') {
            $dados->apresentacao4 = NULL;
        }
        if ($dados->apresentacao5 == '') {
            $dados->apresentacao5 = NULL;
        }
        if ($dados->apresentacao6 == '') {
            $dados->apresentacao6 = NULL;
        }

        $DB->execute("
            UPDATE mdl_sepex_projeto_avaliacao               
            SET resumo1 = ?,
            resumo2 = ?,
            resumo3 = ?,
            resumo4 = ?,
            resumo5 = ?,            
            total_resumo = ?,
            avaliacao1 = ?,
            avaliacao2 = ?,
            avaliacao3 = ?,
            avaliacao4 = ?,
            avaliacao5 = ?,
            avaliacao6 = ?,
            total_avaliacao = ?
            WHERE id_projeto_professor = {$id}", array($dados->resumo1, $dados->resumo2, $dados->resumo3,
            $dados->resumo4, $dados->resumo5, $total_resumo,
            $dados->apresentacao1, $dados->apresentacao2, $dados->apresentacao3,
            $dados->apresentacao4, $dados->apresentacao5, $dados->apresentacao6,
            $total_avaliacao
        ));
    }

    protected function getAvaliacao($idprojeto) {
        global $DB, $USER;

        return $DB->get_records_sql("
        SELECT            
            spp.idprojeto,
            sap.idprofessorprojeto,
            sap.resumo1,
            sap.resumo2,
            sap.resumo3,
            sap.resumo4,
            sap.resumo5,            
            sap.totalresumo,
            sap.avaliacao1,
            sap.avaliacao2,
            sap.avaliacao3,
            sap.avaliacao4,
            sap.avaliacao5,
            sap.avaliacao6,
            sap.totalavaliacao
            FROM mdl_sepex_professor_projeto spp
            INNER JOIN mdl_sepex_avaliacao_projeto sap
            ON sap.idprofessorprojeto = spp.idprofessorprojeto
            WHERE spp.idprojeto = ? AND spp.matrprofessor = ? AND spp.tipo = 'Avaliador'", array($idprojeto, $USER->username));
    }

}
