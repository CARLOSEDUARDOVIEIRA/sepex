<?php

/* Pagina de exibicao e exportacao de arquivos dos projetos */

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require "$CFG->libdir/tablelib.php";

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

$table = new table_sql('uniqueid');
$table->is_downloading($download, 'exportacao', 'exportacao');


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

$table->set_sql("DISTINCT CONCAT(u.firstname,' ',u.lastname) nomeprofessor, spp.tipo, sp.idprojeto, sp.codprojeto,sp.titulo,sp.resumo,sp.tags, sp.dtcadastro,sp.email, sp.idperiodo, sp.turno, sp.idcurso, sp.statusresumo, sp.obsorientador, sp.idcategoria, sp.alocamesa, SUM( sap.totalresumo + sap.totalavaliacao ) notafinal",
                "mdl_sepex_professor_projeto spp 
                 INNER JOIN mdl_sepex_projeto sp ON sp.idprojeto = spp.idprojeto
                 INNER JOIN mdl_user u ON u.username = spp.matrprofessor   
                 LEFT JOIN mdl_sepex_avaliacao_projeto sap ON spp.idprofessorprojeto = sap.idprofessorprojeto",
                 "{$consulta} GROUP BY spp.tipo, sp.idprojeto, sp.statusresumo, sp.codprojeto, sp.titulo, sp.idcategoria, sp.alocamesa
                ");
$table->define_baseurl("$CFG->wwwroot/mod/sepex/views//exportarRelatorio.php?id={$id}&consulta={$consulta}");
$table->out(40, true);

if (!$table->is_downloading()) {
    echo $OUTPUT->footer();
}