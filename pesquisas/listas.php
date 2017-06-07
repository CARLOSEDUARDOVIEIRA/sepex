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
if (isset($_GET['cat']) || isset($_GET['tur'])) {

    $categoria = htmlspecialchars($_GET['cat']);
    $turno = htmlspecialchars($_GET['tur']);
    $mesa = htmlspecialchars($_GET['mesa']);
    $presente = htmlspecialchars($_GET['presente']);
    $nota = htmlspecialchars($_GET['nota']);

    $mform = new FormularioPesquisa("listas.php?id={$id}", array('turno' => $turno, 'cod_categoria' => $categoria, 'mesa' => $mesa, 'presente' => $presente, 'nota' => $nota));
} else {
    $mform = new FormularioPesquisa("listas.php?id={$id}");
}

$mform->display();
$link_voltar = html_writer::start_tag('a', array('href' => '../view.php?id=' . $id));
$link_voltar .= get_string('voltar_menu', 'sepex');
$link_voltar .= html_writer::end_tag('a');
echo $link_voltar;

if ($dados = $mform->get_data()) {

    $teste = "1 = 1";
   
    if ($dados->mesa != null) {
        $teste = $teste . ' AND aloca_mesa = ' . $dados->mesa;
    }

    if ($dados->turno) {
        $teste = $teste . ' AND turno = ' . "'" . turno($dados->turno) . "'";
    }

    if ($dados->categoria) {
        $teste = $teste . ' AND cod_categoria = ' . $dados->categoria;
    }

    $projetos = filtro_pesquisar($teste);

    //------------------------------VIEW---------------------------
    if ($projetos):
        echo '<table class="forumheaderlist table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . get_string('cod_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('titulo_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('categoria', 'sepex') . '</th>';
        echo '<th>' . get_string('situacao', 'sepex') . '</th>';
        echo '<th>' . get_string('orientadores', 'sepex') . '</th>';
        echo '<th>' . get_string('solicita_mesa', 'sepex') . '</th>';
        echo '<th>' . get_string('imprimir', 'sepex') . '</th>';
        echo '<th>' . '</th>';
        echo '</tr>';
        echo '</thead>';
        $tipo = 'orientador';
        foreach ($projetos as $projeto) {
            $apresentacao = obter_dados_apresentacao($projeto->id_projeto);
            $categoria = retorna_categoria($projeto->cod_categoria);
            $tipo = 'orientador';
            $orientador = listar_nome_professores($projeto->id_projeto, $tipo);
//                    echo '<pre>';
//                    print_r($categoria);
//                    echo '</pre>';
            echo '<tbody>';
            echo '<tr>';
            echo'<td><a>' . $projeto->cod_projeto . '</a></td>';
            $titulo = html_writer::start_tag('td');
            $titulo .= html_writer::start_tag('a', array('href' => 'definicao_projeto.php?id=' . $id . '&data=' . $projeto->id_projeto . '&tur=' . $dados->turno . '&cat=' . $dados->categoria,));
            $titulo .= $projeto->titulo;
            $titulo .= html_writer::end_tag('a');
            $titulo .= html_writer::end_tag('td');
            echo $titulo;

            echo'<td><a>' . $categoria[$projeto->cod_categoria]->nome_categoria . '</a></td>';

            //SITUAÇÃO
            if ($projeto->status_resumo) {
                echo'<td>' . $projeto->status_resumo . '</td>';
            } else {
                echo '<td>' . get_string('nao_avaliado', 'sepex') . '</td>';
            }
            //------------
            //ORIENTADOR
            echo'<td>' . $orientador . '</td>';
            //-------------
            if ($projeto->aloca_mesa) {
                echo '<td>' . 'Sim' . '</td>';
            }else{
                echo '<td>' . 'Não' . '</td>';
            }
            $btnEditar = html_writer::start_tag('td');
            //$btnEditar .= html_writer::start_tag('a', array('href'=> 'definicao_projeto.php?id='.$id.'&data='.$projeto->id_projeto.'&tur='.$dados->turno.'&cat='.$dados->categoria,)); 
            $btnEditar .= html_writer::start_tag('input', array('type' => 'button', 'id' => 'editar', 'value' => get_string('editar', 'sepex'), 'class' => 'btn btn-default'));
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
