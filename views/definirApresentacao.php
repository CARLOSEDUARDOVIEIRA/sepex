<?php

/* Tela de definicao de apresentacao dos projetos */

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require '../classes/FormularioFiltro.class.php';
require '../controllers/ApresentacaoController.class.php';
require '../controllers/ProfessorController.class.php';

$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);
$area = optional_param('area', 0, PARAM_INT);
$turno = optional_param('turno', null, PARAM_RAW);
$idcategoria = optional_param('idcategoria', 0, PARAM_INT);

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

$PAGE->set_url('/mod/sepex/definirApresentacao.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('definir_apresentacao', 'sepex'), 2);
echo '<hr>';
$apresentacaocontroller = new ApresentacaoController();
$professorcontroller = new ProfessorController();

if (!empty($area)) {
    $filtro = new FormularioFiltro("definirApresentacao.php?id={$id}", array('areacurso' => $area, 'turno' => $turno, 'idcategoria' => $idcategoria));
} else {
    $filtro = new FormularioFiltro("definirApresentacao.php?id={$id}");
}

$filtro->display();

$link_voltar = html_writer::start_tag('a', array('href' => '../view.php?id=' . $id));
$link_voltar .= get_string('voltar_menu', 'sepex');
$link_voltar .= html_writer::end_tag('a');
echo $link_voltar;

if ($filtro->get_data()) {

    $projetos = $apresentacaocontroller->detailProjetosFiltrados($filtro->get_data());

    if (!empty($projetos)) {

        echo '<table class="forumheaderlist table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . get_string('titulo_projeto', 'sepex') . '</th>';
        echo '<th>' . strtoupper(get_string('periodo', 'sepex')) . '</th>';
        echo '<th>' . get_string('orientadores', 'sepex') . '</th>';
        echo '<th>' . strtoupper(get_string('avaliadores', 'sepex')) . '</th>';
        echo '<th>' . get_string('local', 'sepex') . '</th>';
        echo '<th>' . get_string('horario', 'sepex') . '</th>';
        echo '<th>' . '</th>';
        echo '</tr>';
        echo '</thead>';
        foreach ($projetos as $projeto) {

            echo '<tbody>';
            echo '<tr>';
            $titulo = html_writer::start_tag('td');
            $titulo .= html_writer::start_tag('a', array('href' => 'definicaoProjeto.php?id=' . $id . '&idprojeto=' . $projeto->idprojeto . '&area=' . $filtro->get_data()->areacurso . '&turno=' . $filtro->get_data()->turno . '&idcategoria=' . $filtro->get_data()->idcategoria,));
            $titulo .= $projeto->titulo;
            $titulo .= html_writer::end_tag('a');
            $titulo .= html_writer::end_tag('td');
            echo $titulo;
            echo '<td>' . $projeto->idperiodo . '</td>';
            echo'<td>' . implode(',', $professorcontroller->getNameProfessores($projeto->idprojeto, 'Orientador')) . '</td>';
            echo'<td>' . implode(',', $professorcontroller->getNameProfessores($projeto->idprojeto, 'Avaliador')) . '</td>';
            $apresentacao = $apresentacaocontroller->detailApresentacao($projeto->idprojeto);
            if (isset($apresentacao)) {
                echo '<td>' . $apresentacao->nomelocalapresentacao . '</td>';
                echo '<td>' . date("d/m/Y H:i:s", $apresentacao->dtapresentacao) . '</td>';
            } else {
                echo '<td>' . '</td>';
                echo '<td>' . '</td>';
            }
            $btnEditar = html_writer::start_tag('td');
            $btnEditar .= html_writer::start_tag('a', array('href' => 'definicaoProjeto.php?id=' . $id . '&idprojeto=' . $projeto->idprojeto . '&area=' . $dados->areacurso . '&turno=' . $dados->turno . '&idcategoria=' . $dados->idcategoria,));
            $btnEditar .= html_writer::start_tag('button', array('type' => 'button', 'id' => 'editar', 'class' => 'btn btn-link'));
            $btnEditar .= get_string('editar', 'sepex');
            $btnEditar .= html_writer::end_tag('button');
            $btnEditar .= html_writer::end_tag('td');
            echo $btnEditar;
            echo '</tr>';
            echo '</tbody>';
        }
        echo '</table>';
    } else {
        echo $OUTPUT->notification(get_string('semprojeto', 'sepex'));
    }
}

echo $OUTPUT->footer();
