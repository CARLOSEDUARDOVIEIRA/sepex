<?php

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require "$CFG->libdir/tablelib.php";
require ('../constantes/Constantes.class.php');
require ('../classes/ReportProfessores.class.php');
require '../controllers/AlunoController.class.php';
require '../controllers/ApresentacaoController.class.php';

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
$context = context_system::instance();
$PAGE->set_context($context);

$download = optional_param('download', '', PARAM_ALPHA);
$table = new ReportProfessores('uniqueid', $id);
$table->is_downloading($download, 'tableprofessor', 'tableprofessor');

if (!$table->is_downloading()) {
    $PAGE->set_url('/mod/sepex/views/professor.php', array('id' => $cm->id));
    $PAGE->set_title(format_string($sepex->name));
    $PAGE->set_heading(format_string($sepex->name));
    echo $OUTPUT->header();
}

/* Isso nao eh uma escolha o moodle definiu que essa table_sql so recebe um sql.
  por isso que estou inserindo este sql junto com php na view.
 */
$table->set_sql(
        "sp.idprojeto,
        sp.titulo,
        sp.idcategoria,
        sp.idcurso,
        sp.statusresumo,
        spp.tipo,
        SUM(sap.totalresumo + sap.totalavaliacao) notafinal
        ", "mdl_sepex_professor_projeto spp 
        INNER JOIN mdl_sepex_projeto sp ON spp.idprojeto = sp.idprojeto
        LEFT JOIN mdl_sepex_avaliacao_projeto sap ON spp.idprofessorprojeto = sap.idprofessorprojeto
        ", "spp.matrprofessor = {$USER->username}
        GROUP BY sp.idprojeto, sp.titulo, sp.idcategoria, sp.idcurso, sp.statusresumo, spp.tipo"
);

// Define table columns.
$columns = array();
$headers = array();

$columns[] = 'button';
$headers[] = format_string('');

$columns[] = 'tipo';
$headers[] = format_string('Metodo');

$columns[] = 'categoria';
$headers[] = format_string('Categoria');

$columns[] = 'curso';
$headers[] = format_string('Curso');

$columns[] = 'titulo';
$headers[] = format_string('Titulo');

$columns[] = 'alunos';
$headers[] = format_string('Alunos');

$columns[] = 'nomelocalapresentacao';
$headers[] = format_string('Local Apresentacao');

$columns[] = 'dtapresentacao';
$headers[] = format_string('Data Apresentacao');

$columns[] = 'notafinal';
$headers[] = format_string('Nota final');

$table->define_columns($columns);
$table->define_headers($headers);
$table->define_help_for_headers($help);
$table->sortable(true, 'uniqueid');


$table->define_baseurl("$CFG->wwwroot/mod/sepex/views/professor.php?id={$id}");
$table->out(40, true);

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}

