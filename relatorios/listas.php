<?php

/* EXIBE O RESULTADO DO FILTRO DE PROJETOS -- CONTÉM UM LINK PARA CADASTRO DE LOCAL DE APRESENTAÇÃO POR PROJETO.
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once('../locallib.php');
require_once '../classes/FormularioPesquisa.class.php';
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
echo $OUTPUT->heading(format_string('DEFINIÇÕES DE APRESENTAÇÃO PROJETO'), 2);
echo $OUTPUT->box(format_string(''), 2);


$mform = new FormularioPesquisa("listas.php?id={$id}");


$mform->display();
$link_voltar = html_writer::start_tag('a', array('href' => '../view.php?id=' . $id));
$link_voltar .= get_string('voltar_menu', 'sepex');
$link_voltar .= html_writer::end_tag('a');
echo $link_voltar;

if ($dados = $mform->get_data()) {
    $projetos = filtro_pesquisar($dados);

    //------------------------------VIEW---------------------------
    if ($projetos):
        echo '<table class="forumheaderlist table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . get_string('cod_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('titulo_projeto', 'sepex') . '</th>';
        echo '<th>' . strtoupper(get_string('categoria', 'sepex')) . '</th>';
        echo '<th>' . strtoupper(get_string('situacao', 'sepex')) . '</th>';        
        echo '<th>' . get_string('orientadores', 'sepex') . '</th>';
        echo '<th>' . strtoupper(get_string('solicita_mesa', 'sepex')) . '</th>';
        echo '<th>' . get_string('nota_final', 'sepex') . '</th>';
        echo '<th>' . '</th>';        
        echo '</tr>';
        echo '</thead>';
        $tipo = 'orientador';
        foreach ($projetos as $projeto) {
            $nota_final = ($projeto->nota_final/4);            
            
            $apresentacao = obter_dados_apresentacao($projeto->id_projeto);
            $categoria = retorna_categoria($projeto->cod_categoria);
            $tipo = 'orientador';
            $orientador = listar_nome_professores($projeto->id_projeto, $tipo, $cm->course);

            echo '<tbody>';
            echo '<tr>';
            echo'<td><a>' . $projeto->cod_projeto . '</a></td>';
            $titulo = html_writer::start_tag('td');
            $titulo .= html_writer::start_tag('a', array('href' => '../projeto_aluno/view.php?id=' . $id . '&data=' . $projeto->id_projeto.'&n='.$nota_final,));
            $titulo .= $projeto->titulo;
            $titulo .= html_writer::end_tag('a');
            $titulo .= html_writer::end_tag('td');
            echo $titulo;

            echo'<td><a>' . $categoria[$projeto->cod_categoria]->nome_categoria . '</a></td>';

            //SITUAÇÃO
            if ($projeto->status_resumo != null && $projeto->status_resumo == 1) {
                echo'<td>' .get_string('aprovado', 'sepex')  . '</td>';
            }elseif($projeto->status_resumo != null && $projeto->status_resumo == 0){
                echo'<td>' .get_string('reprovado', 'sepex')  . '</td>';
            }else {
                echo '<td>' . get_string('nao_avaliado', 'sepex') . '</td>';
            }                        
            
            //------------
            //ORIENTADOR
            echo'<td>' . $orientador . '</td>';
            //-------------
            if ($projeto->aloca_mesa) {
                echo '<td>' . 'Sim' . '</td>';
            } else {
                echo '<td>' . 'Não' . '</td>';
            }
            
            echo '<td>' .$nota_final. '</td>';     
            
            $btnEditar = html_writer::start_tag('td');
            $btnEditar .= html_writer::start_tag('a', array('href' => '../projeto_aluno/view.php?id=' . $id . '&data=' . $projeto->id_projeto.'&n='.$nota_final,));
            $btnEditar .= html_writer::start_tag('button', array('type' => 'button', 'class' => 'btn btn-link', 'id' => 'editar'));
            $btnEditar .= get_string('visualizar', 'sepex');
            $btnEditar .= html_writer::end_tag('button');
            $btnEditar .= html_writer::end_tag('td');
            echo $btnEditar;
            echo '</tr>';
            echo '</tbody>';
        }
        echo '</table>';
    else:
        echo $OUTPUT->notification(get_string('semprojeto', 'sepex'));
    endif;
}


echo $OUTPUT->footer();
