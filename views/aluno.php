<?php

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require '../controllers/AlunoController.class.php';
require '../controllers/ApresentacaoController.class.php';
require ('../controllers/ProjetoController.class.php');
require ('../constantes/Constantes.class.php');
require "$CFG->libdir/tablelib.php";
require ('../classes/ReportAlunos.class.php');

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

$showactivity = true;
$timenow = time();
if ((!empty($sepex->timeavailablefrom) && $sepex->timeavailablefrom > $timenow) || (!empty($sepex->timeavailableto) && $timenow > $sepex->timeavailableto)) {
    $showactivity = false;
}

$download = optional_param('download', '', PARAM_ALPHA);
$table = new ReportAlunos('uniqueid', $id, $showactivity);
$table->is_downloading($download, 'tablealuno', 'tablealuno');

if (!$table->is_downloading()) {
    $PAGE->set_url('/mod/sepex/views/aluno.php', array('id' => $cm->id));
    $PAGE->set_title(format_string($sepex->name));
    $PAGE->set_heading(format_string($sepex->name));
    echo $OUTPUT->header();
}

if (!$table->is_downloading()) {
    
    if (!empty($sepex->timeavailablefrom) && $sepex->timeavailablefrom > $timenow) {
        echo $OUTPUT->notification(get_string('notopenyet', 'sepex', userdate($sepex->timeavailablefrom)));
    } else if (!empty($sepex->timeavailableto) && $timenow > $sepex->timeavailableto) {
        echo $OUTPUT->notification(get_string('expired', 'sepex', userdate($sepex->timeavailableto)));
    }

    if ($showactivity) {
        $linkForm = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:10%;'));
        $linkForm .= html_writer::start_tag('a', array('href' => './cadastroProjeto.php?id=' . $id . '&add=1',));
        $linkForm .= html_writer::start_tag('submit', array('class' => 'btn btn-primary', 'style' => 'margin-bottom:5%;'));
        $linkForm .= get_string('inscricao', 'sepex');
        $linkForm .= html_writer::end_tag('a');
        $linkForm .= html_writer::end_tag('div');
        echo $linkForm;
    }
}

if(!is_numeric($USER->username)){
    echo $OUTPUT->notification(get_string('semprojeto', 'sepex'));
    echo $OUTPUT->footer();
    die();
}

/* Isso nao eh uma escolha o moodle definiu que essa table_sql so recebe um sql.
  por isso que estou inserindo este sql junto com php na view.
 */
$table->set_sql(
        "sp.idprojeto,
        sp.codprojeto,
        sp.titulo,
        sp.idcategoria,
        sp.idcurso,
        sp.statusresumo,
        sp.dtcadastro
        ", "mdl_sepex_aluno_projeto sap
        INNER JOIN mdl_sepex_professor_projeto spp ON sap.idprojeto = spp.idprojeto
        INNER JOIN mdl_sepex_projeto sp ON spp.idprojeto = sp.idprojeto
        ", "sap.matraluno = {$USER->username}"
);

// Define table columns.
$columns = array();
$headers = array();

if ($showactivity) {

   // $columns[] = 'chat';
    //$headers[] = format_string('');

    $columns[] = 'edit';
    $headers[] = format_string('');

    $columns[] = 'delete';
    $headers[] = format_string('');
}
if(!$showactivity){
    $columns[] = 'view';
    $headers[] = format_string('');
}
$columns[] = 'codprojeto';
$headers[] = format_string('Codigo');

$columns[] = 'alunos';
$headers[] = format_string('Alunos');

$columns[] = 'titulo';
$headers[] = format_string('Titulo');

$columns[] = 'idcategoria';
$headers[] = format_string('Categoria');

$columns[] = 'curso';
$headers[] = format_string('Curso');

$columns[] = 'dtcadastro';
$headers[] = format_string('Envio');

if (!$showactivity) {
    $columns[] = 'statusresumo';
    $headers[] = format_string('Condiçao do Resumo');

    $columns[] = 'nomelocalapresentacao';
    $headers[] = format_string('Local Apresentacao');

    $columns[] = 'dtapresentacao';
    $headers[] = format_string('Data Apresentacao');
}

$table->define_columns($columns);
$table->define_headers($headers);
// $table->define_help_for_headers($help);
$table->sortable(FALSE, 'uniqueid');

$table->define_baseurl("$CFG->wwwroot/mod/sepex/views/aluno.php?id={$id}");
$table->out(40, true);

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}

