<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Imprime uma instância específica de sepex
 *
 * Você pode ter uma descrição mais longa do arquivo, bem como,
 * Se você gosta, e pode abranger várias linhas.
 *
 * @package    mod_sepex
 * @copyright  2017 Carlos Eduardo Vieira <dullvieira@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require(dirname(dirname(dirname(__FILE__))) . '/config.php');
require(dirname(__FILE__) . '/lib.php');

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
$context_course = context_course::instance($course->id);

$PAGE->set_url('/mod/sepex/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading(format_string($sepex->name));
define('ALUNO_URL_LINK', "./views/aluno.php?id=" . $id);
define('PROFESSOR_URL_LINK', "./views/professor.php?id=" . $id);
define('GERENTE_URL_LINK', "./views/gerente.php?id=" . $id);
echo $OUTPUT->header();

//-------------------------------- ALUNO                       
if (!has_capability('mod/sepex:openformulario', $context_course)) {

    redirect(ALUNO_URL_LINK);
}
//-------------------------------- PROFESSOR
elseif (has_capability('mod/sepex:openprofessor', $context_course)) {
    redirect(PROFESSOR_URL_LINK);
}
//-------------------------------- GERENTE
else {
    redirect(GERENTE_URL_LINK);
}


//Fim da página
echo $OUTPUT->footer();
