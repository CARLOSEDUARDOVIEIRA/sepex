<?php

/* PAGINA DE EXIBICAO DOS RELATORIOS DOS PROJETOS */

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require '../classes/FormularioPesquisa.class.php';
require '../controllers/ProjetoController.class.php';
require '../constantes/Constantes.class.php';
require '../controllers/ProfessorController.class.php';

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

require_login($course, true, $cm);

$PAGE->set_url('/mod/sepex/relProjetos.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('header_relatorios', 'sepex'), 3);

$filtro = new FormularioPesquisa("relProjetos.php?id={$id}");
$filtro->display();

$linkvoltar = html_writer::start_tag('a', array('href' => '../view.php?id=' . $id));
$linkvoltar .= get_string('voltar_menu', 'sepex');
$linkvoltar .= html_writer::end_tag('a');
echo $linkvoltar;
echo '<hr>';

$projetocontroller = new ProjetoController();
$constantes = new Constantes();
$professorcontroller = new ProfessorController();

if (!empty($filtro->get_data())) {
    $projetos = $projetocontroller->getProjetosFiltrados($filtro->get_data());
    echo get_string('numeroregistros', 'sepex', count($projetos));
    
//------------------------------VIEW---------------------------
    if (isset($projetos)) {
        echo '<table class="forumheaderlist table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . get_string('cod_projeto', 'sepex') . '</th>';
        echo '<th>' . get_string('titulo_projeto', 'sepex') . '</th>';
        echo '<th>' . strtoupper(get_string('categoria', 'sepex')) . '</th>';
        echo '<th>' . get_string('situacao', 'sepex') . '</th>';
        echo '<th>' . get_string('orientadores', 'sepex') . '</th>';
        echo '<th>' . strtoupper(get_string('solicita_mesa', 'sepex')) . '</th>';
        echo '<th>' . get_string('nota_final', 'sepex') . '</th>';
        echo '<th>' . '</th>';
        echo '</tr>';
        echo '</thead>';
        foreach ($projetos as $projeto) {
            $notafinal = ($projeto->notafinal / 4);
            echo '<tbody>';
            echo'<td>' . $projeto->codprojeto . '</td>';

            $titulo = html_writer::start_tag('td');
            $titulo .= html_writer::start_tag('a', array('href' => '../projetoAluno/view.php?id=' . $id . '&idprojeto=' . $projeto->idprojeto . '&n=' . $notafinal,));
            $titulo .= $projeto->titulo;
            $titulo .= html_writer::end_tag('a');
            $titulo .= html_writer::end_tag('td');
            echo $titulo;

            echo'<td>' . $constantes->detailCategorias($projeto->idcategoria) . '</td>';

            if ($projeto->statusresumo == 1) {
                echo'<td>' . get_string('aprovado', 'sepex') . '</td>';
            } elseif ($projeto->statusresumo == 0) {
                echo'<td>' . get_string('reprovado', 'sepex') . '</td>';
            } else {
                echo '<td>' . get_string('nao_avaliado', 'sepex') . '</td>';
            }

            echo '<td>' . implode(',', $professorcontroller->getNameProfessores($projeto->idprojeto, 'Orientador')) . '</td>';

            if ($projeto->alocamesa) {
                echo '<td>' . 'Sim' . '</td>';
            } else {
                echo '<td>' . 'Não' . '</td>';
            }

            echo '<td>' . $notafinal . '</td>';

            $btnEditar = html_writer::start_tag('td');
            $btnEditar .= html_writer::start_tag('a', array('href' => '../projeto_aluno/view.php?id=' . $id . '&idprojeto=' . $projeto->idprojeto . '&n=' . $notafinal,));
            $btnEditar .= html_writer::start_tag('button', array('type' => 'button', 'class' => 'btn btn-link', 'id' => 'editar'));
            $btnEditar .= get_string('visualizar', 'sepex');
            $btnEditar .= html_writer::end_tag('button');
            $btnEditar .= html_writer::end_tag('td');
            echo $btnEditar;
        }

        echo '</table>';
    }
}




echo $OUTPUT->footer();
