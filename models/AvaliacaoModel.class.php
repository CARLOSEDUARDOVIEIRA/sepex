<?php

/** AvaliacaoModel
 * @author Carlos Eduardo Vieira
 */
class AvaliacaoModel {

    protected function save($idprojeto, $notas) {
        global $DB, $USER;

        $notas->idprofessorprojeto = $DB->get_records('sepex_professor_projeto', array('matrprofessor' => $USER->username, 'idprojeto' => $idprojeto), null, 'idprojeto,idprofessorprojeto')[$idprojeto]->idprofessorprojeto;
        $DB->insert_record("sepex_avaliacao_projeto", $notas);
    }

    protected function update($idprojeto, $notas) {
        global $DB, $USER;

        $DB->execute("
            UPDATE mdl_sepex_avaliacao_projeto sap
            INNER JOIN mdl_sepex_professor_projeto spp
            ON sap.idprofessorprojeto = spp.idprofessorprojeto
            SET resumo1 = ?,
            resumo2 = ?,
            resumo3 = ?,
            resumo4 = ?,
            resumo5 = ?,            
            totalresumo = ?,
            avaliacao1 = ?,
            avaliacao2 = ?,
            avaliacao3 = ?,
            avaliacao4 = ?,
            avaliacao5 = ?,
            avaliacao6 = ?,
            avaliacao7 = ?,
            avaliacao8 = ?,
            avaliacao9 = ?,
            avaliacao10 = ?,
            avaliacao11 = ?,
            avaliacao12 = ?,
            totalavaliacao = ?
            WHERE spp.matrprofessor = {$USER->username} AND spp.idprojeto = {$idprojeto}", array($notas->resumo1,
            $notas->resumo2,
            $notas->resumo3,
            $notas->resumo4,
            $notas->resumo5,
            $notas->totalresumo,
            $notas->avaliacao1,
            $notas->avaliacao2,
            $notas->avaliacao3,
            $notas->avaliacao4,
            $notas->avaliacao5,
            $notas->avaliacao6,
            $notas->avaliacao7,
            $notas->avaliacao8,
            $notas->avaliacao9,
            $notas->avaliacao10,
            $notas->avaliacao11,
            $notas->avaliacao12,
            $notas->totalavaliacao
        ));
    }

    /** Retorna as notas inseridas de avaliacao do projeto, pelo professor logado.
     * @return - array de notas referentes a apresentacao do projeto.
     */
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
            sap.avaliacao7,
            sap.avaliacao8,
            sap.avaliacao9,
            sap.avaliacao10,
            sap.avaliacao11,
            sap.avaliacao12,
            sap.totalavaliacao
            FROM mdl_sepex_professor_projeto spp
            INNER JOIN mdl_sepex_avaliacao_projeto sap
            ON sap.idprofessorprojeto = spp.idprofessorprojeto
            WHERE spp.idprojeto = ? AND spp.matrprofessor = ? AND spp.tipo = 'Avaliador'", array($idprojeto, $USER->username))[$idprojeto];
    }

    protected function savePresencaAlunos($idprojeto, $alunos) {
        global $DB;

        foreach ($alunos as $matricula => $presenca) {
            $DB->execute("
            UPDATE mdl_sepex_aluno_projeto                
                SET presenca = ?
                WHERE idprojeto = {$idprojeto} AND matraluno = {$matricula}", array($presenca));
        }
    }

}
