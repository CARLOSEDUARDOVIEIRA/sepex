<?php

/**
 * TELA APRESENTADA AOS ALUNOS AO FINAL DAS INSCRIÇÕES
 *
 * @package    mod_sepex
 * @copyright  2017 Carlos Eduardo Vieira  <dullvieira@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once('../locallib.php');

$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);
$id_projeto = optional_param('data', 0, PARAM_INT);

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
$coursecontext = context_course::instance($course->id);
$event = \mod_sepex\event\course_module_viewed::create(array(
            'objectid' => $PAGE->cm->instance,
            'context' => $PAGE->context,
        ));
$PAGE->set_url('/mod/sepex/avaliacao_avaliador.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();         
echo $OUTPUT->heading(format_string('PROJETO ALUNO'), 2);
echo $OUTPUT->box(format_string(''), 2);  
    
    if (!empty($id_projeto)) {    
        $projeto = listar_projeto_por_id($id_projeto);        
        $tipo = 'orientador';
        $orientadores = listar_nome_professores($id_projeto, $tipo);
        $categoria = retorna_categoria($projeto[$id_projeto]->cod_categoria);                                   
        $avaliadores = listar_nome_professores($id_projeto, 'avaliador');
        $apresentacao = obter_dados_apresentacao($projeto[$id_projeto]->id_projeto);
        $alunos = listar_nome_alunos($id_projeto);
        $integrantes = array();    
        foreach($alunos as $aluno){                
            array_push($integrantes, $aluno->name);                
        }     
        $lista_alunos = implode(", ", $integrantes);
        
    }
    //View header of page
    $header  = html_writer::start_tag('div', array('style' => 'margin-bottom:5%;'));                                                     
    $header .= html_writer::start_tag('h5', array('class'=>'page-header'));
    $header.= $projeto[$id_projeto]->cod_projeto.' - '.$projeto[$id_projeto]->titulo;
    $header .= html_writer::end_tag('h5');
    $header.= '<b>'.get_string('alunos_projeto', 'sepex').'</b>'.': '.$lista_alunos.'</br>';
    $header.= '<b>'.get_string('curso', 'sepex').'</b>'.': '.$projeto[$id_projeto]->curso_cod_curso.'</br>';
    $header.= '<b>'.get_string('turno', 'sepex').'</b>'.': '.$projeto[$id_projeto]->turno.'</br>';
    $header.= '<b>'.get_string('orientadores', 'sepex').'</b>'.': '.$orientadores.'</br>';
    $header.= '<b>'.strtoupper(get_string('categoria', 'sepex')).'</b>'.': '.$categoria[$projeto[$id_projeto]->cod_categoria]->nome_categoria;
    $header .= html_writer::end_tag('div');
    echo $header;
    if(isset($projeto[$id_projeto]->resumo)){
        $resumo = html_writer::start_tag('div', array('style' => 'margin-left:5%; margin-right:10%;text-align:justify;'));
        $resumo .= html_writer::start_tag('p').$projeto[$id_projeto]->resumo.html_writer::end_tag('p');
        $resumo .= html_writer::end_tag('div');
        echo $resumo;
               
        echo '<p></br>'.'<b>'.get_string('palavra_chave', 'sepex').'</b>'.':  '.$projeto[$id_projeto]->tags.'</p>';
    }

    echo '<p>'.'</br></br>'.get_string('local_apresentacao', 'sepex').'</p></br>';                      
    
    if (isset($apresentacao[$projeto[$id_projeto]->id_projeto]->nome_local_apresentacao)){
        echo '<p>'.'<b>'.strtoupper(get_string('avaliadores', 'sepex')).'</b>'.': '.$avaliadores.'</p>';
        echo '<p>'.'<b>'.get_string('local', 'sepex').'</b>'.':  '.$apresentacao[$projeto[$id_projeto]->id_projeto]->nome_local_apresentacao.'</p>';
        echo '<p>'.'<b>'.get_string('apresentacao', 'sepex').'</b>'.':  '.date("d/m/Y H:i:s", $apresentacao[$projeto[$id_projeto]->id_projeto]->data_apresentacao).'</p>';                        
    }else{
        echo '<p>'.'<b>'.strtoupper(get_string('avaliadores', 'sepex')).'</b>'.': '.get_string('aguardando_definicao', 'sepex').'</p>';
        echo '<p>'.'<b>'.get_string('local', 'sepex').'</b>'.':  '.get_string('aguardando_definicao', 'sepex').'</p>';
        echo '<p>'.'<b>'.get_string('apresentacao', 'sepex').'</b>'.':  '.get_string('aguardando_definicao', 'sepex').'</p>';                        
    }
        
    
    
    
    
    
    
    
    
//Fim da página
echo $OUTPUT->footer();
