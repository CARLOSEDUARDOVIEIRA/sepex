<?php

/* Pagina de exibicao e exportacao de relatorio de professores */

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require "$CFG->libdir/tablelib.php";
require ('../classes/ReportAvaliacao.class.php');

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
$table = new ReportAvaliacao('uniqueid', $id);
$table->is_downloading($download, 'exportacao', 'exportacao');


if (!$table->is_downloading()) {
    $PAGE->set_url('/mod/sepex/views/relAvaliacao.php', array('id' => $cm->id));
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

$table->set_sql("spp.idprofessorprojeto, sp.idprojeto, sp.titulo, sp.turno, sp.areacurso,sp.idcategoria, sp.idcurso, CONCAT(u.firstname,' ',u.lastname) nomeprofessor,
                (sap.totalresumo + sap.totalavaliacao) notafinal
                ", "mdl_sepex_professor_projeto spp
                    LEFT JOIN mdl_sepex_avaliacao_projeto sap on spp.idprofessorprojeto = sap.idprofessorprojeto
                    INNER JOIN mdl_user u on spp.matrprofessor = u.username
                    INNER JOIN mdl_sepex_projeto sp ON sp.idprojeto = spp.idprojeto 
                ", "{$consulta} AND tipo = 'Avaliador'");

// Define table columns.
$columns = array();
$headers = array();
$help = array();

$columns[] = 'titulo';
$headers[] = format_string('Titulo');
$help[] = NULL;

$columns[] = 'curso';
$headers[] = format_string('Curso');
$help[] = NULL;

$columns[] = 'area';
$headers[] = format_string('Area do curso');
$help[] = NULL;

$columns[] = 'nomeprofessor';
$headers[] = format_string('Nome do professor');
$help[] = NULL;

$columns[] = 'notafinal';
$headers[] = format_string('Nota final');
$help[] = NULL;

$table->define_columns($columns);
$table->define_headers($headers);
$table->define_help_for_headers($help);
$table->sortable(true, 'uniqueid');
                
                
$table->define_baseurl("$CFG->wwwroot/mod/sepex/views/relAvaliacao.php?id={$id}&consulta={$consulta}");
$table->out(40, true);

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}