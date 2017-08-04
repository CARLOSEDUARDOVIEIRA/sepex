<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
//Icons by:
//<div>Icons made by <a href="http://www.flaticon.com/authors/madebyoliver" title="Madebyoliver">Madebyoliver</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
/**
 * Biblioteca interna de funções para módulo sepex
 *
 * Todas as funções específicas do sepex, necessárias para implementar o módulo
 * Lógica, deve ir aqui. Nunca inclua este arquivo do seu lib.php!
 *
 * @package    mod_sepex
 * @copyright  2017 Carlos Eduardo Vieira <dullvieira@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');

defined('MOODLE_INTERNAL') || die();


/* * Método responsável por trazer do banco as informações sobre os projetos de um aluno
 * @param type $aluno -> matricula do aluno que deseja listar as informações
 * @return aluno
 */


/**
 * Método responsável por criar uma tabela listando os projetos de determinado aluno
 * @param type $usuario = este usuario é o aluno que queremos listar os projetos.
 * @param type $id é o id da página para enviar ao delete_form.php
 * ATENÇÃO -- Na tag table estou usando uma classe do plugin 'forum' para receber tratamentos de css e js,
 * em caso de anomalias na exibição - tente remover essa classe forumheaderlist.
 */
function listar_projetos_aluno($usuario, $id) {

    $resultado = select_projetos_aluno($usuario);
    if ($resultado != null || $resultado != ''):
        //Caso o moodle tenha o plugin módulo use o css dele através da classe forumheaderlist
        echo '<table class="forumheaderlist table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . get_string('cod_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('titulo_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('categoria_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('envio', 'sepex') . '</th>';
        echo '<th>' . strtoupper(get_string('editar', 'sepex')) . '</th>';
        echo '<th>' . get_string('apagar', 'sepex') . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($resultado as $projeto) {
            $apresentacao = obter_dados_apresentacao($projeto->id_projeto);
            echo '<tr>';
            echo'<td><a>' . $projeto->cod_projeto . '</a></td>';

            $titulo = html_writer::start_tag('td');
            $titulo .= html_writer::start_tag('a', array('href' => './cadastro_sepex/cadastro_sepex.php?id=' . $id . '&data=' . $projeto->id_projeto . '&update=1',));
            $titulo .= $projeto->titulo;
            $titulo .= html_writer::end_tag('a');
            $titulo .= html_writer::end_tag('td');
            echo $titulo;

            $categoria = retorna_categoria($projeto->cod_categoria);

            echo'<td><a>' . $categoria[$projeto->cod_categoria]->nome_categoria . '</a></td>';

            echo'<td><a>' . $projeto->data_cadastro . '</a></td>';

            $editar = html_writer::start_tag('td');
            $editar .= html_writer::start_tag('a', array('id' => 'btnEdit', 'href' => './cadastro_sepex/cadastro_sepex.php?id=' . $id . '&data=' . $projeto->id_projeto . '&update=1',));
            $editar .= html_writer::start_tag('img', array('src' => 'pix/edit.png'));
            $editar .= html_writer::end_tag('a');
            $editar .= html_writer::end_tag('td');
            echo $editar;

            $delete = html_writer::start_tag('td');
            $delete .= html_writer::start_tag('a', array('href' => './cadastro_sepex/acao.php?id=' . $id . '&proj=' . $projeto->id_projeto . '&acao=2',));
            $delete .= html_writer::start_tag('img', array('src' => 'pix/delete.png'));
            $delete .= html_writer::end_tag('a');
            $delete .= html_writer::end_tag('td');
            echo $delete;

            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    endif;
}

function listar_projetos_aluno_apresentacao($usuario, $id) {

    $resultado = select_projetos_aluno($usuario);
    if ($resultado != null || $resultado != ''):
        //Caso o moodle tenha o plugin módulo use o css dele através da classe forumheaderlist
        echo '<table class="forumheaderlist table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . get_string('cod_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('titulo_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('categoria_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('envio', 'sepex') . '</th>';
        echo '<th>' . get_string('informacao_projeto', 'sepex') . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($resultado as $projeto) {
            echo '<tr>';
            echo'<td><a>' . $projeto->cod_projeto . '</a></td>';

            $titulo = html_writer::start_tag('td');
            $titulo .= html_writer::start_tag('a', array('href' => './projeto_aluno/view.php?id=' . $id . '&data=' . $projeto->id_projeto,));
            $titulo .= $projeto->titulo;
            $titulo .= html_writer::end_tag('a');
            $titulo .= html_writer::end_tag('td');
            echo $titulo;

            $categoria = retorna_categoria($projeto->cod_categoria);

            echo'<td><a>' . $categoria[$projeto->cod_categoria]->nome_categoria . '</a></td>';

            echo'<td><a>' . $projeto->data_cadastro . '</a></td>';

            $link = html_writer::start_tag('td');
            $link .= html_writer::start_tag('a', array('href' => './projeto_aluno/view.php?id=' . $id . '&data=' . $projeto->id_projeto,));
            $link .= get_string('visualizar', 'sepex');
            $link .= html_writer::end_tag('a');
            $link .= html_writer::end_tag('td');
            echo $link;

            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    endif;
}

function header_projetos_professor() {
    echo '<table class="forumheaderlist table table-striped">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>' . get_string('responsabilidade', 'sepex') . '</th>';
    echo '<th>' . get_string('cod_projeto', 'sepex') . '</th>';
    echo '<th>' . get_string('titulo_projeto', 'sepex') . '</th>';
    echo '<th>' . strtoupper(get_string('categoria', 'sepex')) . '</th>';
    echo '<th>' . strtoupper(get_string('curso', 'sepex')) . '</th>';
    echo '<th>' . get_string('situacao_final', 'sepex') . '</th>';
    echo '<th>' . get_string('avaliar', 'sepex') . '</th>';
    echo '</tr>';
    echo '</thead>';
}

function listar_projetos_professor($usuario, $id) {
    global $PAGE;
    $resultado = select_projetos_professor($usuario);
    if ($resultado != null || $resultado != ''):
        header_projetos_professor();
        echo '<tbody>';
        foreach ($resultado as $projeto) {
            echo '<tr>';
            echo'<td><a>' . $projeto->tipo . '</a></td>';
            echo'<td><a>' . $projeto->cod_projeto . '</a></td>';

            $titulo = html_writer::start_tag('td');
            if ($projeto->tipo == 'avaliador') {
                $titulo .= html_writer::start_tag('a', array('id' => 'titulo', 'href' => './avaliacao_professor/avaliacao_avaliador.php?id=' . $id . '&data=' . $projeto->id_projeto,));
            } else {
                $titulo .= html_writer::start_tag('a', array('id' => 'titulo', 'href' => './avaliacao_professor/avaliacao_orientador.php?id=' . $id . '&data=' . $projeto->id_projeto,));
            }
            $titulo .= $projeto->titulo;
            $titulo .= html_writer::end_tag('a');
            $titulo .= html_writer::end_tag('td');
            echo $titulo;

            $categoria = retorna_categoria($projeto->cod_categoria);

            echo'<td><a>' . $categoria[$projeto->cod_categoria]->nome_categoria . '</a></td>';
            echo'<td><a>' . $projeto->curso_cod_curso . '</a></td>';

            if ($projeto->tipo == 'avaliador') {
                echo'<td><a>' . ($projeto->nota_final / 2) . '</a></td>';
            } else {
                if ($projeto->status_resumo != null && $projeto->status_resumo == 1) {
                    echo'<td>' . get_string('aprovado', 'sepex') . '</td>';
                } elseif ($projeto->status_resumo != null && $projeto->status_resumo == 0) {
                    echo'<td>' . get_string('reprovado', 'sepex') . '</td>';
                } else {
                    echo '<td>' . get_string('nao_avaliado', 'sepex') . '</td>';
                }
            }

            $avaliar = html_writer::start_tag('td');
            if ($projeto->tipo == 'avaliador') {
                $avaliar .= html_writer::start_tag('a', array('id' => 'btnEdit', 'href' => './avaliacao_professor/avaliacao_avaliador.php?id=' . $id . '&data=' . $projeto->id_projeto,));
            } else {
                $avaliar .= html_writer::start_tag('a', array('id' => 'btnEdit', 'href' => './avaliacao_professor/avaliacao_orientador.php?id=' . $id . '&data=' . $projeto->id_projeto,));
            }
            $avaliar .= html_writer::start_tag('img', array('src' => 'pix/edit.png'));
            $avaliar .= html_writer::end_tag('a');
            $avaliar .= html_writer::end_tag('td');
            echo $avaliar;
            echo '</tr>';
        }
        echo '</tbody>';

        echo '</table>';
    endif;
}


/* * Exibe os projetos de acordo com um filtro de area, turno, categoria.
 * @global type $DB
 * @param type $dados
 */

function obter_projetos_por_area_turno_categoria($dados) {
    global $DB;

    $projeto = $DB->get_records_sql("
            SELECT            
            sp.id_projeto,
            sp.cod_projeto,
            sp.titulo,
            sp.resumo,
            sp.email,
            sp.tags,
            sp.cod_periodo,
            sp.turno,
            sp.aloca_mesa                    
            FROM mdl_sepex_projeto sp
            INNER JOIN mdl_sepex_projeto_curso spc ON spc.projeto_id_projeto = sp.id_projeto
            INNER JOIN mdl_sepex_projeto_professor spp ON spp.id_projeto = sp.id_projeto 
            WHERE sp.area_curso = ? AND sp.turno = ? AND sp.cod_categoria = ? AND spp.status_resumo = 1
            ORDER BY sp.cod_periodo, spc.curso_cod_curso     
            ", array($dados->area_curso, $dados->turno, $dados->cod_categoria));
    return $projeto;
}


/* * Método responsável por listar os projetos pelo código do professor. 
 * @global type $DB
 * @param type $professor
 * @return type projetos por professor.
 */

function select_projetos_professor($usuario) {
    global $DB;
//Exibir os projetos do aluno
    $resultado = $DB->get_records_sql("
            SELECT
            sp.id_projeto,
            sp.titulo,
            sp.cod_projeto,
            sp.cod_categoria,
            sp.data_cadastro,
            spp.tipo,
            spc.curso_cod_curso,
            spp.status_resumo,
            SUM( spa.total_resumo + spa.total_avaliacao ) nota_final
            FROM mdl_sepex_projeto_professor spp 
            LEFT JOIN mdl_sepex_projeto sp ON sp.id_projeto = spp.id_projeto
            LEFT JOIN mdl_sepex_projeto_curso spc ON sp.id_projeto = spc.projeto_id_projeto
            LEFT JOIN mdl_sepex_projeto_avaliacao spa ON spp.id_projeto_professor = spa.id_projeto_professor
            WHERE spp.professor_cod_professor = ?
            GROUP BY sp.id_projeto, sp.titulo, sp.cod_projeto, sp.cod_categoria, sp.data_cadastro, spp.tipo, spc.curso_cod_curso, spp.status_resumo ORDER BY spp.tipo"
            , array($usuario));
    return $resultado;
}

/* * LISTAR ID PROFESSOR - Método responsável por listar os professores que fazem parte de um projeto.
 * Não foi possível realizar um JOIN para trazer o nome dos professores porque a função get_records_sql só trouxe 1 professor.
 * @global type $DB
 * @param type $projeto
 * @return type
 */


function listar_nome_professores($id_projeto, $tipo, $curso) {
    global $DB;
    $resultado = $DB->get_records_sql("
           SELECT                
                u.username,
                CONCAT(u.firstname,' ',u.lastname) as name                
                FROM mdl_course c
                INNER JOIN mdl_context ct ON ct.instanceid = c.id
                INNER JOIN mdl_role_assignments ra ON ra.contextid = ct.id
                INNER JOIN mdl_user u ON u.id = ra.userid
                INNER JOIN mdl_role r on r.id = ra.roleid
                INNER JOIN mdl_sepex_projeto_professor spp
                ON spp.professor_cod_professor = u.username
                INNER JOIN mdl_sepex_projeto sp ON sp.id_projeto = spp.id_projeto
                WHERE ct.contextlevel = 50 AND c.id = {$curso} AND r.shortname = 'editingteacher'                                                                                    
                AND sp.id_projeto = {$id_projeto} AND spp.tipo = '{$tipo}'");

    $professores = array();
    foreach ($resultado as $professor) {
        array_push($professores, $professor->name);
    }
    $professor = implode(", ", $professores);
    return $professor;
}

function listar_usuarios_por_curso($user, $course) {
    global $DB;
    $professores = $DB->get_records_sql("
        SELECT
            u.username,
            CONCAT(u.firstname,' ',u.lastname) as name            
            FROM mdl_course c
            INNER JOIN mdl_context ct ON ct.instanceid = c.id
            INNER JOIN mdl_role_assignments ra ON ra.contextid = ct.id
            INNER JOIN mdl_user u ON u.id = ra.userid
            INNER JOIN mdl_role r on r.id = ra.roleid
            WHERE ct.contextlevel = 50 AND c.id = {$course} AND r.shortname = '{$user}' ORDER BY u.firstname");
    return $professores;
}



/** Listar codigo dos professores por id projeto.
 * @global type $DB
 * @param type $id_projeto
 * @return type cod_professor
 */
function viewGerente($id) {

    
}

function enviar_email($USER, $dados) {
    global $PAGE;
    $date = new DateTime("now", core_date::get_user_timezone_object());
    $dataAtual = userdate($date->getTimestamp());
    $nao_responda = core_user::get_noreply_user();
    $PAGE->navbar->add('email');
    $constante = new Constantes();
    
//    $alunos = implode(";",$dados->aluno_matricula);          
    $titulo_email = 'Confirmação de inscrição XX SEPEX - UCV';
    $corpo_email = "Parabéns!\n"
            . "Você inscreveu seu trabalho na XX Semana de Ensino Pesquisa e Extensão - SEPEX"
            . "\nA partir de agora você pode acompanhar o status do seu projeto pelo moodle."
            . "\nCertifique-se de que a matrícula de todos os integrantes do seu grupo está"
            . " correta e que todos estão vendo o projeto em sua pagina principal de inscrição SEPEX "
            . "\nTítulo do trabalho:" . $dados->titulo
            . "\nCategoria: " . $constante->detailCategorias($dados->idcategoria)
            . "\nData de inscrição: " . $dataAtual;

    if (!$resultado = email_to_user($USER, $nao_responda, $titulo_email, $corpo_email)) {
        die("Erro no envio do email!");
    }
}


/* * Metodo responsável por criar os locais de apresentação
 * @param type $nome
 */

function criar_local_apresentacao($nome) {
    global $DB;
    $local_apresentacao = new stdClass();
    $local_apresentacao->nome_local_apresentacao = $nome;
    $DB->insert_record("sepex_local_apresentacao", $local_apresentacao);
}

/* * Metodo responsavel por listar todos os locais de apresentacao cadastrados no sistema.
 * @global type $DB
 * @return array com os locais de apresentacao cadastrados no sistema.
 */

function listar_locais_apresentacao() {
    global $DB;
    $lista_locais = $DB->get_records("sepex_local_apresentacao", array());
    $locais = array();
    foreach ($lista_locais as $local) {
        array_push($locais, $local);
    }
    return $locais;
}

function apagar_local_apresentacao($id) {
    global $DB;
    $DB->delete_records('sepex_local_apresentacao', array("id_local_apresentacao" => $id));
    $DB->delete_records('sepex_projeto_definicao', array("id_local_apresentacao" => $id));
}

/* * Metodo responsavel por inserir projetos nos lugares de apresentação 
 * @global type $DB
 * @param type $id_projeto
 * @param type $local
 * @param type $dia
 * @param type $hora
 */

function guardar_definicao_projeto($id_projeto, $local, $data) {
    global $DB;

    $projeto = new stdClass();
    $projeto->id_projeto = $id_projeto;
    $projeto->data_apresentacao = $data;
    $projeto->id_local_apresentacao = $local;
    $DB->insert_record("sepex_projeto_definicao", $projeto);
}

function alterar_definicao_projeto($id_projeto, $local, $data) {
    global $DB;

    $DB->execute("
        UPDATE mdl_sepex_projeto_definicao spd                                          
            SET spd.data_apresentacao = ?,                 
            spd.id_local_apresentacao = ?                 
            WHERE spd.id_projeto = {$id_projeto} ", array($data, $local));


    $tipo = 'avaliador';
    delete_professor_projeto($id_projeto, $tipo);
}

function header_definicao_projeto($sepex, $cm, $projeto, $orientadores, $id_projeto, $mform) {
    global $OUTPUT;
    echo $OUTPUT->header();
    echo $OUTPUT->heading(format_string('Definições do projeto'), 2);
    echo $OUTPUT->box(format_module_intro('sepex', $sepex, $cm->id), 'generalbox', 'intro');
    $header = html_writer::start_tag('div', array('style' => 'margin-bottom:5%;'));
    $header .= html_writer::start_tag('h5', array('class' => 'page-header'));
    $header.= $projeto[$id_projeto]->cod_projeto . ' - ' . $projeto[$id_projeto]->titulo;
    $header .= html_writer::end_tag('h5');
    $header.= '<b>' . get_string('curso', 'sepex') . '</b>' . ': ' . $projeto[$id_projeto]->curso_cod_curso . '</br>';
    $header.= '<b>' . get_string('turno', 'sepex') . '</b>' . ': ' . $projeto[$id_projeto]->turno . '</br>';
    $header.= '<b>' . get_string('periodo', 'sepex') . '</b>' . ': ' . $projeto[$id_projeto]->cod_periodo . '</br>';
    $header.= '<b>' . get_string('orientadores', 'sepex') . '</b>' . ': ' . $orientadores;
    $header .= html_writer::end_tag('div');
    echo $header;

    $mform->display();

    echo $OUTPUT->footer();
}

function guardar_avaliacao_orientador($dados, $id_projeto, $cod_professor) {
    global $DB;

    $date = new DateTime("now", core_date::get_user_timezone_object());
    $dataAtual = userdate($date->getTimestamp());

    $DB->execute("
        UPDATE mdl_sepex_projeto sp
        INNER JOIN mdl_sepex_projeto_professor spp
        ON sp.id_projeto = spp.id_projeto
        SET sp.resumo = ?,
        sp.tags = ?,
        spp.status_resumo = ?,        
        spp.obs_orientador = ?,
        spp.data_avaliacao = ?
        WHERE sp.id_projeto = {$id_projeto} AND professor_cod_professor = {$cod_professor} AND tipo = 'orientador' ", array($dados->resumo[text], $dados->tags, $dados->condicao, $dados->comentario, $dataAtual));
}

function listar_dados_avaliacao_orientador($id_projeto, $cod_professor) {
    global $DB;

    $avaliacao_orientador = $DB->get_records_sql("
        SELECT            
            spp.id_projeto,
            spp.status_resumo,
            spp.obs_orientador
            FROM mdl_sepex_projeto_professor spp            
            WHERE spp.id_projeto = ? AND spp.professor_cod_professor = ? AND spp.tipo = 'orientador'", array($id_projeto, $cod_professor));
    return $avaliacao_orientador;
}

function guardar_avaliacao_avaliador($dados, $id_projeto, $cod_professor) {
    global $DB;

    $id = $DB->get_records_sql("
        SELECT
            id_projeto,	  		
            id_projeto_professor		
            FROM mdl_sepex_projeto_professor            
            WHERE id_projeto = ? AND professor_cod_professor = ? AND tipo = 'avaliador'", array($id_projeto, $cod_professor));

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

    $avaliacao = new stdClass();
    $avaliacao->id_projeto_professor = $id[$id_projeto]->id_projeto_professor;
    $avaliacao->resumo1 = $dados->resumo1;
    $avaliacao->resumo2 = $dados->resumo2;
    $avaliacao->resumo3 = $dados->resumo3;
    $avaliacao->resumo4 = $dados->resumo4;
    $avaliacao->resumo5 = $dados->resumo5;
    $total_resumo = $dados->resumo1 + $dados->resumo2 + $dados->resumo3 + $dados->resumo4 + $dados->resumo5;
    $avaliacao->total_resumo = $total_resumo;
    $avaliacao->avaliacao1 = $dados->apresentacao1;
    $avaliacao->avaliacao2 = $dados->apresentacao2;
    $avaliacao->avaliacao3 = $dados->apresentacao3;
    $avaliacao->avaliacao4 = $dados->apresentacao4;
    $avaliacao->avaliacao5 = $dados->apresentacao5;
    $avaliacao->avaliacao6 = $dados->apresentacao6;
    $total_avaliacao = $dados->apresentacao1 + $dados->apresentacao2 + $dados->apresentacao3 + $dados->apresentacao4 + $dados->apresentacao5 + $dados->apresentacao6;

    $avaliacao->total_avaliacao = $total_avaliacao;
    $DB->insert_record("sepex_projeto_avaliacao", $avaliacao);
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

function guardar_presenca_aluno($dados, $id_projeto) {
    global $DB;

    foreach ($dados->types as $indice => $aluno) {
        $DB->execute("
            UPDATE mdl_sepex_aluno_projeto                
                SET presenca = ?
                WHERE id_projeto = {$id_projeto} AND aluno_matricula = {$indice}", array($aluno));
    }
}

function listar_dados_avaliacao_avaliador($id_projeto, $cod_professor) {
    global $DB;

    $avaliacao_avaliador = $DB->get_records_sql("
        SELECT            
            spp.id_projeto,
            spa.id_projeto_professor,
            spa.resumo1,
            spa.resumo2,
            spa.resumo3,
            spa.resumo4,
            spa.resumo5,            
            spa.total_resumo,
            spa.avaliacao1,
            spa.avaliacao2,
            spa.avaliacao3,
            spa.avaliacao4,
            spa.avaliacao5,
            spa.avaliacao6,
            spa.total_avaliacao
            FROM mdl_sepex_projeto_professor spp
            INNER JOIN mdl_sepex_projeto_avaliacao spa
            ON spa.id_projeto_professor = spp.id_projeto_professor
            WHERE spp.id_projeto = ? AND spp.professor_cod_professor = ? AND spp.tipo = 'avaliador'", array($id_projeto, $cod_professor));
    return $avaliacao_avaliador;
}

function listar_nome_alunos($id_projeto) {
    global $DB;
    $alunos_projeto = $DB->get_records_sql("
        SELECT
            u.username,
            CONCAT(u.firstname,' ',u.lastname) as name            
            FROM mdl_sepex_aluno_projeto sap                        
            INNER JOIN mdl_user u ON u.username = sap.aluno_matricula            
            WHERE sap.id_projeto = {$id_projeto}");

    return $alunos_projeto;
}

function listar_presenca_aluno_matricula($id_projeto, $matricula) {
    global $DB;

    $alunos_presenca = $DB->get_records_sql("
        SELECT
            id_projeto,
            aluno_matricula,
            presenca
            FROM mdl_sepex_aluno_projeto                                    
            WHERE id_projeto = {$id_projeto} AND aluno_matricula = {$matricula}");
    return $alunos_presenca;
}

function listar_situacao_resumo($id_projeto) {
    global $DB;
    $situacao_projeto = $DB->get_records_sql("
        SELECT
            id_projeto,
            status_resumo,
            obs_orientador
            FROM mdl_sepex_projeto_professor                                    
            WHERE id_projeto = {$id_projeto} AND tipo = 'orientador'");

    return $situacao_projeto;
}

//------------

function filtro_pesquisar($dados) {
    global $DB;

    $consulta = "1 = 1";

    if($dados->cod_curso){
        $consulta = $consulta . ' AND spc.curso_cod_curso = ' . "'".$dados->cod_curso."'";
    }
    
    if ($dados->area_curso) {
        $consulta = $consulta . ' AND area_curso = ' . $dados->area_curso;
    }
    if ($dados->mesa != null) {
        $consulta = $consulta . ' AND aloca_mesa = ' . $dados->mesa;
    }
    if ($dados->turno != null) {
        $consulta = $consulta . ' AND turno = ' . "'" . $dados->turno . "'";
    }
    if ($dados->categoria) {
        $consulta = $consulta . ' AND cod_categoria = ' . $dados->categoria;
    }

    if ($dados->situacao_resumo == 2) {
        $consulta = $consulta . ' AND spp.status_resumo is null';
    }elseif ($dados->situacao_resumo != null) {
        $consulta = $consulta . ' AND spp.status_resumo = ' . $dados->situacao_resumo;
    }


    $query = $DB->get_records_sql("
    SELECT sp.id_projeto, spp.status_resumo, sp.cod_projeto, sp.titulo, sp.cod_categoria, sp.aloca_mesa, SUM( spa.total_resumo + spa.total_avaliacao ) nota_final
    FROM mdl_sepex_projeto_professor spp
    LEFT JOIN mdl_sepex_projeto sp ON sp.id_projeto = spp.id_projeto
    LEFT JOIN mdl_sepex_projeto_avaliacao spa ON spp.id_projeto_professor = spa.id_projeto_professor
    LEFT JOIN mdl_sepex_projeto_curso spc ON spc.projeto_id_projeto = sp.id_projeto
    WHERE {$consulta}
    GROUP BY sp.id_projeto, spp.status_resumo, sp.cod_projeto, sp.titulo, sp.cod_categoria, sp.aloca_mesa
    ORDER BY nota_final DESC");
    return $query;
}
