<?php

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require ('../controllers/ProfessorController.class.php');
require ('../controllers/ProjetoController.class.php');
require ('../constantes/Constantes.class.php');
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
$context_course = context_course::instance($course->id);

$PAGE->set_url('/mod/sepex/views/professor.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading(format_string($sepex->name));

$professorcontroller = new ProfessorController();
$projetocontroller = new ProjetoController();
$constantes = new Constantes();

echo $OUTPUT->header();

$projetos = $professorcontroller->getProjetosProfessor($USER->username);
echo get_string('numeroregistros', 'sepex', count($projetos));

echo '<table class="forumheaderlist table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th></th>';
echo '<th>' . get_string('titulo_projeto', 'sepex') . '</th>';
echo '<th>' . strtoupper(get_string('categoria', 'sepex')) . '</th>';
echo '<th>' . strtoupper(get_string('curso', 'sepex')) . '</th>';
echo '<th>' . get_string('situacao_final', 'sepex') . '</th>';
echo '<th>' . get_string('avaliar', 'sepex') . '</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

foreach ($projetos as $projeto) {
    echo '<tr>';
    echo'<td>' . $projeto->tipo . '</td>';

    $titulo = html_writer::start_tag('td');
    if ($projeto->tipo == 'Avaliador') {
        $titulo .= html_writer::start_tag('a', array('id' => 'titulo', 'href' => './avaliacao_professor/avaliacao_avaliador.php?id=' . $id . '&data=' . $projeto->idprojeto,));
    } else {
        $titulo .= html_writer::start_tag('a', array('id' => 'titulo', 'href' => './avaliacao_professor/avaliacao_orientador.php?id=' . $id . '&data=' . $projeto->idprojeto,));
    }

    $titulo .= $projeto->titulo;
    $titulo .= html_writer::end_tag('a');
    $titulo .= html_writer::end_tag('td');
    echo $titulo;

    echo'<td>' . $constantes->detailCategorias($projeto->idcategoria) . '</td>';
    echo'<td>' . $constantes->detailCursos($projeto->idcurso) . '</td>';

    if ($projeto->tipo == 'Avaliador') {
        echo'<td>' . ($projeto->notafinal / 2) . '</td>';
    } else {
        if (!isset($projeto->statusresumo)) {
            echo '<td>' . get_string('nao_avaliado', 'sepex') . '</td>';
        } elseif ($projeto->statusresumo) {
            echo'<td>' . get_string('aprovado', 'sepex') . '</td>';
        } else {
            echo'<td>' . get_string('reprovado', 'sepex') . '</td>';
        }
    }

    $avaliar = html_writer::start_tag('td');
    if ($projeto->tipo == 'Avaliador') {
        $avaliar .= html_writer::start_tag('a', array('id' => 'btnEdit', 'href' => './avaliacao_professor/avaliacao_avaliador.php?id=' . $id . '&data=' . $projeto->idprojeto,));
    } else {
        $avaliar .= html_writer::start_tag('a', array('id' => 'btnEdit', 'href' => './avaliacao_professor/avaliacao_orientador.php?id=' . $id . '&data=' . $projeto->idprojeto,));
    }
    $avaliar .= html_writer::start_tag('img', array('src' => '../pix/edit.png'));
    $avaliar .= html_writer::end_tag('a');
    $avaliar .= html_writer::end_tag('td');
    echo $avaliar;
    echo '</tr>';

    echo '</tbody>';
}
echo '</table>';

echo $OUTPUT->footer();


