<?php

/**
 * Description of ProjetoModel
 *
 * @author Carlos Eduardo Vieira
 */
class ProjetoModel {

    /** metodo que irah salvar um novo projeto no bd, <b>Optei por nao utilizar 
     * transactions pelo fato de o moodle da ucv estar desatualizado
     * e estudando o forum de desenvolvedores moodle, percebi que estavam com 
     * duvidas sobre a eficacia dessa funcionalidade no framework.
     * <https://moodle.org/mod/forum/discuss.php?d=135847>, de qualquer forma em 
     * caso de necessidade de acordo com a evolucao do evento sepex isso pode 
     * ser implementado.</b>
     */
    protected function save($projeto) {
        global $USER, $DB;

        $area = new Constantes();
        $date = new DateTime("now", core_date::get_user_timezone_object());
        $projeto->dtcadastro = userdate($date->getTimestamp());
        $projeto->codprojeto = $this->createCodigoProjeto($projeto->idcategoria);
        $projeto->email = $USER->email;
        $projeto->areacurso = $area->getAreaCurso($projeto->idcurso);
        $projeto->resumo = $projeto->resumo[text];
        $idprojeto = $DB->insert_record('sepex_projeto', $projeto, $returnid = true);

        $orientador = (object) array('idprojeto' => $idprojeto,
                    'matrprofessor' => $projeto->matrprofessor,
                    'tipo' => 'Orientador'
        );

        $DB->insert_record("sepex_professor_projeto", $orientador);
        $this->saveAluno($idprojeto, $projeto->matraluno);
    }

    protected function update($projeto) {
        global $USER, $DB;

        $area = new Constantes();
        $date = new DateTime("now", core_date::get_user_timezone_object());
        $projeto->dtcadastro = userdate($date->getTimestamp());

        $projetoantigo = $DB->get_record("sepex_projeto", array('idprojeto' => $projeto->idprojeto));

        if ($projetoantigo->idcategoria != $projeto->idcategoria) {
            $projeto->codprojeto = $this->createCodigoProjeto($projeto->idcategoria);
        } else {
            $projeto->codprojeto = $projetoantigo->codprojeto;
        }
        $projeto->areacurso = $area->getAreaCurso($projeto->idcurso);
        $projeto->email = $USER->email;
        $projeto->resumo = $projeto->resumo[text];
        $DB->execute("
            UPDATE mdl_sepex_projeto sp
            INNER JOIN mdl_sepex_professor_projeto spp
            ON sp.idprojeto = spp.idprojeto
                SET sp.areacurso = ?,
                sp.idcategoria = ?, 
                sp.codprojeto = ?,
                sp.dtcadastro = ?,                
                sp.email = ?,
                sp.alocamesa = ?,
                sp.idperiodo = ?,
                sp.resumo = ?,
                sp.statusresumo = ?,
                sp.obsorientador = ?,
                sp.tags = ?,
                sp.turno = ?,
                sp.titulo = ?,
                sp.idcurso = ?,
                spp.matrprofessor = ?
                WHERE sp.idprojeto = {$projeto->idprojeto}", array($projeto->areacurso,
            $projeto->idcategoria,
            $projeto->codprojeto,
            $projeto->dtcadastro,
            $projeto->email,
            $projeto->alocamesa,
            $projeto->idperiodo,
            $projeto->resumo,
            $projeto->statusresumo,
            $projeto->obsorientador,
            $projeto->tags,
            $projeto->turno,
            $projeto->titulo,
            $projeto->idcurso,
            $projeto->matrprofessor
                )
        );

        //Estudarei como melhorar este update dos alunos.
        $response = $DB->delete_records('sepex_aluno_projeto', array("idprojeto" => $projeto->idprojeto));
        if ($response) {
            $this->saveAluno($projeto->idprojeto, $projeto->matraluno);
        }
    }

    protected function delete($idprojeto) {
        global $DB;

        $avaliadores = $DB->get_records('sepex_professor_projeto', array('idprojeto' => $idprojeto, 'tipo' => 'avaliador'));
        if ($avaliadores) {
            foreach ($avaliadores as $avaliacao) {
                $DB->delete_records('sepex_avaliacao_projeto', array("idprofessorprojeto" => $avaliacao->idprofessorprojeto));
            }
        }
        $DB->delete_records('sepex_aluno_projeto', array("idprojeto" => $idprojeto));
        $DB->delete_records('sepex_professor_projeto', array("idprojeto" => $idprojeto));
        $DB->delete_records('sepex_projeto', array("idprojeto" => $idprojeto));
        $DB->delete_records('sepex_definicao_projeto', array("idprojeto" => $idprojeto));
    }

    protected function detail($idprojeto) {
        global $DB;
        return $DB->get_records('sepex_projeto', array("idprojeto" => $idprojeto));
    }

    protected function getDefinicaoProjeto($idprojeto) {
        global $DB;
        return $DB->get_records_sql("
        SELECT            
            sdp.idprojeto,
            sla.nomelocalapresentacao,
            sdp.dtapresentacao
            FROM mdl_sepex_definicao_projeto sdp
            INNER JOIN mdl_sepex_local_apresentacao sla ON sla.idlocalapresentacao = sdp.idlocalapresentacao    
            WHERE sdp.idprojeto = ?", array($idprojeto));
    }

    protected function getAvaliacaoProjeto($professores) {
        global $DB;
        $avaliacaoprojeto = array();
        if ($professores) {
            foreach ($professores as $avaliacao) {
                array_push($avaliacaoprojeto, $DB->get_records('sepex_avaliacao_projeto', array("idprofessorprojeto" => $avaliacao->idprofessorprojeto)));
            }
        }
        return $avaliacaoprojeto;
    }

    private function createCodigoProjeto($idcategoria) {
        global $DB;
        $numero = $DB->count_records('sepex_projeto', array('idcategoria' => $idcategoria));
        $categoria = [
            "1" => "EGR",
            "2" => "EST",
            "3" => "INC",
            "4" => "INO",
            "5" => "PE",
            "6" => "PI",
            "7" => "RS",
            "8" => "TL",
            "9" => "TCC",
            "10" => "VID"            
        ];
        if ($numero == null):
            $numero = 0;
        endif;

        $codigo = 'SEP17' . $categoria[$idcategoria] . $numero;

        return $codigo;
    }

    private function saveAluno($idprojeto, $student) {
        global $USER, $DB;
        $alunocadastrante = (object) array('matraluno' => $USER->username,
                    'idprojeto' => $idprojeto
        );
        $DB->insert_record("sepex_aluno_projeto", $alunocadastrante);

        $demaisalunos = explode(";", $student);

        if (count($demaisalunos) > 1) {
            foreach ($demaisalunos as $aluno) {
                $matraluno = trim($aluno, " \t,"); //remove espacos e retira uma possivel , da ultima matricula informada.
                $alunovalido = is_numeric($matraluno) && strlen($matraluno) == 10 && $matraluno != $USER->username;
                if ($alunovalido) {
                    $alunoinsert = (object) array('matraluno' => $matraluno,
                                'idprojeto' => $idprojeto
                    );
                    $DB->insert_record("sepex_aluno_projeto", $alunoinsert);
                }
            }
        }
    }

    protected function getProjetosDoUsuario() {
        global $USER, $DB;

        return $DB->get_records_sql("
            SELECT
            sp.idprojeto,
            sp.titulo,
            sp.codprojeto,
            sp.idcategoria,
            sp.dtcadastro
            FROM mdl_sepex_aluno_projeto sap
            INNER JOIN mdl_sepex_projeto sp ON sp.idprojeto = sap.idprojeto
            WHERE sap.matraluno = ? ", array($USER->username));
    }

    protected function getUsuarioPorCurso($typeuser, $course) {
        global $DB;
        return $DB->get_records_sql("
        SELECT
            u.username,
            CONCAT(u.firstname,' ',u.lastname) as name            
            FROM mdl_course c
            INNER JOIN mdl_context ct ON ct.instanceid = c.id
            INNER JOIN mdl_role_assignments ra ON ra.contextid = ct.id
            INNER JOIN mdl_user u ON u.id = ra.userid
            INNER JOIN mdl_role r on r.id = ra.roleid
            WHERE ct.contextlevel = 50 AND c.id = {$course} AND r.shortname = '{$typeuser}' OR r.shortname = 'manager' ORDER BY u.firstname");
    }

    protected function getProjetosFiltrados($consulta) {
        global $DB;

        return $DB->get_records_sql("
            SELECT sp.idprojeto, sp.statusresumo, sp.codprojeto, sp.titulo, sp.idcategoria, sp.alocamesa, SUM( sap.totalresumo + sap.totalavaliacao ) notafinal
            FROM mdl_sepex_professor_projeto spp
            INNER JOIN mdl_sepex_projeto sp ON sp.idprojeto = spp.idprojeto
            LEFT JOIN mdl_sepex_avaliacao_projeto sap ON spp.idprofessorprojeto = sap.idprofessorprojeto            
            WHERE {$consulta}
            GROUP BY sp.idprojeto, sp.statusresumo, sp.codprojeto, sp.titulo, sp.idcategoria, sp.alocamesa
            ORDER BY notafinal DESC");
    }

    protected function getProjetosPorCategoria($idcategoria) {
        global $DB;

        return $DB->get_records_sql("
            SELECT sp.idprojeto, CONCAT(u.firstname,' ',u.lastname) as orientador, sp.titulo,
            sp.resumo, sp.tags, sp.turno, sp.idcurso
            FROM mdl_sepex_projeto sp
            INNER JOIN mdl_sepex_professor_projeto spp ON sp.idprojeto = spp.idprojeto
            INNER JOIN mdl_user u ON spp.matrprofessor = u.username
            WHERE sp.idcategoria = ? AND spp.tipo = 'Orientador' AND sp.statusresumo = 1       
            ORDER BY sp.idcurso, sp.turno, sp.idprojeto", array($idcategoria));
    }

}
