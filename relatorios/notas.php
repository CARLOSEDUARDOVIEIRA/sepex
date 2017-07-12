<?php

/* EXIBE O RESULTADO DO FILTRO DE PROJETOS -- CONTÉM UM LINK PARA CADASTRO DE LOCAL DE APRESENTAÇÃO POR PROJETO.
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once('../locallib.php');
require_once '../classes/FormularioNota.class.php';
global $DB, $CFG, $PAGE;
$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('sepex', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $sepex = $DB->get_record('sepex', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($s) {
    $sepex = $DB->get_record('sepex', array('id' => $s), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $sepex->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('sepex', $sepex->id, $course->id, false, MUST_EXIST);
} else {
    error('Você deve especificar um course_module ID ou um ID de instância');
}

$lang = current_language();
require_login($course, true, $cm);

$event = \mod_sepex\event\course_module_viewed::create(array(
            'objectid' => $PAGE->cm->instance,
            'context' => $PAGE->context,
        ));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $sepex);
$event->trigger();
$PAGE->set_url('/mod/sepex/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string('RELATÓRIO DE NOTAS E LOCAL APRESENTAÇÃO'), 2);
echo $OUTPUT->box(format_string(''), 2);


$mform = new FormularioNota("notas.php?id={$id}");


$mform->display();
$link_voltar = html_writer::start_tag('a', array('href' => '../view.php?id=' . $id));
$link_voltar .= get_string('voltar_menu', 'sepex');
$link_voltar .= html_writer::end_tag('a');
echo $link_voltar;

if ($dados = $mform->get_data()) {
    $dados->situacao_resumo = 2;
    $projetos = filtro_pesquisar($dados);

    //------------------------------VIEW---------------------------
    if ($projetos):
        echo '<table class="forumheaderlist table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . get_string('cod_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('titulo_projeto', 'sepex') . '</th>';
        echo '<th>' . strtoupper(get_string('categoria', 'sepex')) . '</th>';
        echo '<th>' . strtoupper(get_string('curso', 'sepex')) . '</th>';
        echo '<th>' . get_string('local', 'sepex') . '</th>';
        echo '<th>' . get_string('horario', 'sepex') . '</th>';
        echo '<th>' . get_string('orientadores', 'sepex') . '</th>';
        echo '<th>' . strtoupper(get_string('avaliadores', 'sepex')) . '</th>';
        echo '<th>' . strtoupper(get_string('alunos_projeto', 'sepex')) . '</th>';
        echo '<th>' . get_string('nota_final', 'sepex') . '</th>';
        echo '</tr>';
        echo '</thead>';
        foreach ($projetos as $projeto) {
            $nota_final = ($projeto->nota_final / 4);

            $apresentacao = obter_dados_apresentacao($projeto->id_projeto);
            $categoria = retorna_categoria($projeto->cod_categoria);
            $orientador = listar_nome_professores($projeto->id_projeto, 'orientador', $cm->course);
            $avaliadores = listar_nome_professores($projeto->id_projeto, 'avaliador', $cm->course);
            $apresentacao = obter_dados_apresentacao($projeto->id_projeto);

            echo '<tbody>';
            echo '<tr>';
            echo'<td>' . $projeto->cod_projeto . '</td>';
            $titulo = html_writer::start_tag('td');
            $titulo .= html_writer::start_tag('a', array('href' => '../projeto_aluno/view.php?id=' . $id . '&data=' . $projeto->id_projeto . '&n=' . $nota_final,));
            $titulo .= $projeto->titulo;
            $titulo .= html_writer::end_tag('a');
            $titulo .= html_writer::end_tag('td');
            echo $titulo;

            echo'<td>' . $categoria[$projeto->cod_categoria]->nome_categoria . '</td>';

            //SITUAÇÃO
            if (isset($dados->cod_curso)):
                echo '<td>' . $dados->cod_curso . '</td>';
            else:
                echo '<td></td>';
            endif;

            //APRESENTACAO
            if (isset($apresentacao[$projeto->id_projeto]->nome_local_apresentacao)):
                echo '<td>' . $apresentacao[$projeto->id_projeto]->nome_local_apresentacao . '</td>';
                echo '<td>' . date("d/m/Y H:i:s", $apresentacao[$projeto->id_projeto]->data_apresentacao) . '</td>';
            else:
                echo '<td></td>';
                echo '<td></td>';
            endif;
            //------------
            //ORIENTADOR
            echo'<td>' . $orientador . '</td>';
            echo'<td><a>' . $avaliadores . '</a></td>';
            //-------------
            $alunos = listar_nome_alunos($projeto->id_projeto);
            $integrantes = array();
            foreach ($alunos as $aluno) {
                array_push($integrantes, $aluno->name);
            }
            $lista_alunos = implode(", ", $integrantes);
            echo '<td>' . $lista_alunos . '</td>';
            if($dados->nota):
            echo '<td>' . $nota_final . '</td>';
            else:
                echo '<td></td>';
            endif;
            echo '</tr>';
            echo '</tbody>';
        }
        echo '</table>';
    else:
        echo $OUTPUT->notification(get_string('semprojeto', 'sepex'));
    endif;
}


echo $OUTPUT->footer();
