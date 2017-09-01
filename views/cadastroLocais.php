<?php

/* Pagina responsavel em permitir o gerente cadastrar locais de apresentacao para o projeto */

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require '../controllers/LocalController.class.php';
require '../classes/Local.class.php';

$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);
$add = optional_param('add', 0, PARAM_INT);
$delete = optional_param('delete', 0, PARAM_INT);
$idlocal = optional_param('idlocal', 0, PARAM_INT);

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

$PAGE->set_url('/mod/sepex/local_apresentacao/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('gerenciar_locais', 'sepex'), 2);
define('VIEW_URL_LINK', "../view.php?id=" . $id);
define('LOCAIS_URL_LINK', "./cadastroLocais.php?id=" . $id.'&add=1');
echo '<hr>';

$localcontroller = new LocalController();

$link_voltar = html_writer::start_tag('a', array('href' => '../view.php?id=' . $id, 'style' => 'margin-bottom:3%;'));
$link_voltar .= get_string('voltar_menu', 'sepex');
$link_voltar .= html_writer::end_tag('a');
echo $link_voltar;

$local = new Local("cadastroLocais.php?id={$id}&add=1", array('course' => $cm->course));
if (!empty($add)) {
    if ($local->is_cancelled()) {
        redirect(VIEW_URL_LINK);
    } else if ($local->get_data()) {
        $localcontroller->save($local->get_data());
        echo("<meta http-equiv='refresh' content='0'>");
    } else {
        $local->display();
    }
} elseif (!empty($delete)) {
    if ($delete == 1) {
        echo $OUTPUT->confirm(get_string("delete", "sepex"), "cadastroLocais.php?id={$id}&delete=2&idlocal={$idlocal}", $CFG->wwwroot . '/mod/sepex/view.php?id=' . $id);
    } elseif ($delete == 2) {
        $localcontroller->delete($idlocal);
        redirect(LOCAIS_URL_LINK);
    }
}

$locais = $localcontroller->getLocais();
echo '<table class="forumheaderlist table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>' . get_string('localapresentacao', 'sepex') . '</th>';
echo '<th>' . get_string('apagar', 'sepex') . '</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
foreach ($locais as $local) {
    echo '<tr>';
    echo'<td>' . $local->nomelocalapresentacao . '</td>';
    $delete = html_writer::start_tag('td');
    $delete .= html_writer::start_tag('a', array('href' => 'cadastroLocais.php?id=' . $id . '&idlocal=' . $local->idlocalapresentacao . '&delete=1'));
    $delete .= get_string('apagar', 'sepex');
    $delete .= html_writer::end_tag('a');
    $delete .= html_writer::end_tag('td');
    echo $delete;
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';

echo $OUTPUT->footer();
