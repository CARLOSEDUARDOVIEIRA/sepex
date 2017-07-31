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
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = required_param('id', PARAM_INT);
$s  = optional_param('s', 0, PARAM_INT);
$acao  = optional_param('acao', 0, PARAM_INT);

if ($id) {
    $cm         = get_coursemodule_from_id('sepex', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $sepex  = $DB->get_record('sepex', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($s) {
    $sepex  = $DB->get_record('sepex', array('id' => $s), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $sepex->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('sepex', $sepex->id, $course->id, false, MUST_EXIST);
} else {
    error('Você deve especificar um course_module ID ou um ID de instância');
}

$lang = current_language();
require_login($course, true, $cm);
$context_course = context_course::instance($course -> id);       

$PAGE->set_url('/mod/sepex/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading(format_string($sepex->name));
//A saída começa aqui.
echo $OUTPUT->header();
     
    //-------------------------------- ALUNO                       
    if (!has_capability('mod/sepex:openformulario', $context_course)) {
        $showactivity = true;

        $timenow = time();
        if (!empty($sepex->timeavailablefrom) && $sepex->timeavailablefrom > $timenow) {
            echo $OUTPUT->notification(get_string('notopenyet', 'sepex', userdate($sepex->timeavailablefrom)));
            $showactivity = false;
        } else if (!empty($sepex->timeavailableto) && $timenow > $sepex->timeavailableto) {
            echo $OUTPUT->notification(get_string('expired', 'sepex', userdate($sepex->timeavailableto)));
            $showactivity = false;
        }
        $usuario = $USER->username;
        if ($showactivity) {
            
            $linkForm = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:10%;'));
            $linkForm .= html_writer::start_tag('a', array('href' => './views/cadastroProjeto.php?id=' . $id . '&add=1',));
            $linkForm .= html_writer::start_tag('submit', array('class' => 'btn btn-primary', 'style' => 'margin-bottom:5%;'));
            $linkForm .= get_string('inscricao', 'sepex');
            $linkForm .= html_writer::end_tag('a');
            $linkForm .= html_writer::end_tag('div');
            echo $linkForm;
                        
            //Se for aluno redireciono para o formulario.                        
//            listar_projetos_aluno($usuario, $id);
//            if ($acao){
//                $proj =  htmlspecialchars($_POST['proj']);
//                if($acao == 2){
//                    apagar_formulario($proj);
//                    echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=view.php?id=$id'>";
//                }
//            }
        }else{
            //listar_projetos_aluno_apresentacao($usuario,$id);
        }
    }        
    //-------------------------------- PROFESSOR
    elseif (has_capability('mod/sepex:openprofessor', $context_course)) {            
            $usuario = $USER->username;
           // listar_projetos_professor($usuario,$id);

    }
        //-------------------------------- GERENTE
    else {
            echo $OUTPUT->heading(format_string('Organização sepex'), 2);
            echo $OUTPUT->box(format_module_intro('sepex', $sepex, $cm->id), 'generalbox', 'intro');
            viewGerente($id);
    }       
    
    
//Fim da página
    echo $OUTPUT->footer();