<?php

/* Pagina de exibicao e exportacao de relatorio de alunos presentes na apresentacao */

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require "$CFG->libdir/tablelib.php";
require ('../classes/ReportPresentes.class.php');

$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);
$consulta = required_param('consulta', PARAM_RAW);

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
$context = context_system::instance();
$PAGE->set_context($context);

$download = optional_param('download', '', PARAM_ALPHA);
$table = new ReportPresentes('uniqueid', $id);
$table->is_downloading($download, 'relatoriopresencas', 'relatoriopresencas');


if (!$table->is_downloading()) {
    $PAGE->set_url('/mod/sepex/views/relPresentes.php', array('id' => $cm->id));
    $PAGE->set_title(format_string($sepex->name));
    $PAGE->set_heading($course->fullname);
    echo $OUTPUT->header();

    $voltar = html_writer::start_tag('a', array('href' => './relProjetos.php?id=' . $id,));
    $voltar .= html_writer::start_tag('img', array('src' => '../pix/left-arrow.png'));
    $voltar .= ' ' . get_string('voltar_menu', 'sepex');
    $voltar .= html_writer::end_tag('a');
    echo $voltar;
    echo '<hr>';
}

$table->set_sql("sap.idalunoprojeto, sap.presenca, sp.codprojeto, sp.idprojeto, sp.titulo, sp.turno, sp.idcategoria, sp.idcurso, sp.idperiodo, sp.turno, CONCAT(u.firstname,' ',u.lastname) nomealuno                
                ", "mdl_sepex_aluno_projeto sap
                    INNER JOIN mdl_user u on sap.matraluno = u.username
                    INNER JOIN mdl_sepex_projeto sp ON sp.idprojeto = sap.idprojeto 
                ", "1 = 1");

// Define table columns.
$columns = array();
$headers = array();

$columns[] = 'codprojeto';
$headers[] = format_string('Codigo');

$columns[] = 'titulo';
$headers[] = format_string('Titulo');

$columns[] = 'curso';
$headers[] = format_string('Curso');

$columns[] = 'periodo';
$headers[] = format_string('Periodo');

$columns[] = 'turno';
$headers[] = format_string('Turno');

$columns[] = 'alunos';
$headers[] = format_string('Aluno');

$columns[] = 'presenca';
$headers[] = format_string('Presenca');

$table->define_columns($columns);
$table->define_headers($headers);
$table->sortable(false, 'uniqueid');

$table->define_baseurl("$CFG->wwwroot/mod/sepex/views/relPresentes.php?id={$id}&consulta={$consulta}");
$table->out(40, true);

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}