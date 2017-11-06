<?php

/* Pagina de exibicao e exportacao de arquivos dos projetos */

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require '../classes/Report.class.php';

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

$table = new Report('uniqueid', $id);
$table->is_downloading($download, 'relatoriogeral', 'relatoriogeral');

if (!$table->is_downloading()) {
    $PAGE->set_url('/mod/sepex/views/exportarRelatorio.php', array('id' => $cm->id));
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

$table->set_count_sql("SELECT COUNT(1) FROM mdl_sepex_projeto WHERE {$consulta}");

$table->set_sql("DISTINCT sp.idprojeto,  sp.codprojeto,sp.titulo,sp.resumo,sp.tags, sp.dtcadastro,sp.email,
                 sp.idperiodo, sp.turno, sp.idcurso, sp.statusresumo, sp.obsorientador, sp.idcategoria,
                 sp.alocamesa,sp.areacurso, SUM( sap.totalresumo + sap.totalavaliacao ) notafinal
                ", "mdl_sepex_professor_projeto spp 
                 INNER JOIN mdl_sepex_projeto sp ON sp.idprojeto = spp.idprojeto 
                 LEFT JOIN mdl_sepex_avaliacao_projeto sap ON spp.idprofessorprojeto = sap.idprofessorprojeto
                ", "{$consulta} GROUP BY  sp.idprojeto, sp.codprojeto,sp.titulo,sp.resumo, sp.tags,
                 sp.dtcadastro,sp.email, sp.idperiodo, sp.turno, sp.idcurso, sp.statusresumo, sp.obsorientador,
                 sp.idcategoria, sp.alocamesa, sp.areacurso 
                ");

// Define table columns.
$columns = array();
$headers = array();

$columns[] = 'idprojeto';
$headers[] = format_string('Id');

$columns[] = 'codprojeto';
$headers[] = format_string('Codigo');

$columns[] = 'titulo';
$headers[] = format_string('Titulo');

$columns[] = 'resumo';
$headers[] = format_string('Resumo');

$columns[] = 'tags';
$headers[] = format_string('Tags');

$columns[] = 'dtcadastro';
$headers[] = format_string('Data de Cadastro');

$columns[] = 'periodo';
$headers[] = format_string('Periodo');

$columns[] = 'turno';
$headers[] = format_string('Turno');

$columns[] = 'areacurso';
$headers[] = format_string('Area Curso');

$columns[] = 'alocamesa';
$headers[] = format_string('Solicita mesa');

$columns[] = 'categoria';
$headers[] = format_string('Categoria');

$columns[] = 'curso';
$headers[] = format_string('Curso');

$columns[] = 'alunos';
$headers[] = format_string('Aluno(s)');

$columns[] = 'orientador';
$headers[] = format_string('Orientador');

$columns[] = 'avaliador';
$headers[] = format_string('Avaliador(es)');

$columns[] = 'nomelocalapresentacao';
$headers[] = format_string('Local Apresentacao');

$columns[] = 'dtapresentacao';
$headers[] = format_string('Data Apresentacao');

$columns[] = 'notafinal';
$headers[] = format_string('Nota final');

$table->define_columns($columns);
$table->define_headers($headers);
$table->sortable(false, 'uniqueid');

$table->define_baseurl("$CFG->wwwroot/mod/sepex/views//exportarRelatorio.php?id={$id}&consulta={$consulta}");
$table->out(40, true);

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}