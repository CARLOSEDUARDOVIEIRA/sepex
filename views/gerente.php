<?php

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');

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

$PAGE->set_url('/mod/sepex/views/gerente.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading(format_string($sepex->name));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('organizacaosepex','sepex'), 2);
echo $OUTPUT->box(format_module_intro('sepex', $sepex, $cm->id), 'generalbox', 'intro');
echo '<hr>';

$criarLocalApresentacao = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:2%;'));
$criarLocalApresentacao .= html_writer::start_tag('a', array('href' => './local_apresentacao/view.php?id=' . $id,));
$criarLocalApresentacao .= html_writer::start_tag('img', array('src' => '../pix/cadloc.png'));
$criarLocalApresentacao .= get_string('criar_local_apresentacao', 'sepex');
$criarLocalApresentacao .= html_writer::end_tag('a');
$criarLocalApresentacao .= html_writer::end_tag('div');
echo $criarLocalApresentacao;
echo '<hr>';

$localApresentacao = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:2%;'));
$localApresentacao .= html_writer::start_tag('a', array('href' => './definicoes_projeto/view.php?id=' . $id,));
$localApresentacao .= html_writer::start_tag('img', array('src' => '../pix/locapre.png'));
$localApresentacao .= get_string('definir_local_apresentacao', 'sepex');
$localApresentacao .= html_writer::end_tag('a');
$localApresentacao .= html_writer::end_tag('div');
echo $localApresentacao;
echo '<hr>';

$exibirRelatorio = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:2%;'));
$exibirRelatorio .= html_writer::start_tag('a', array('href' => './relatorios/listas.php?id=' . $id,));
$exibirRelatorio .= html_writer::start_tag('img', array('src' => '../pix/relcad.png'));
$exibirRelatorio .= get_string('exibir_relatorios', 'sepex');
$exibirRelatorio .= html_writer::end_tag('a');
$exibirRelatorio .= html_writer::end_tag('div');
echo $exibirRelatorio;
echo '<hr>';

$relnotas = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:2%;'));
$relnotas .= html_writer::start_tag('a', array('href' => './relatorios/notas.php?id=' . $id,));
$relnotas .= html_writer::start_tag('img', array('src' => '../pix/relnotas.png'));
$relnotas .= format_string('Exibir relatório de notas e local de apresentação');
$relnotas .= html_writer::end_tag('a');
$relnotas .= html_writer::end_tag('div');
echo $relnotas;
echo '<hr>';

echo $OUTPUT->footer();
