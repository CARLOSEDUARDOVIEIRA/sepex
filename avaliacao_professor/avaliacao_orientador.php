<?php

/* EXIBE A TELA PARA AVALIAÇÃO DO PROJETO PELOS PROFESSORES ORIENTADORES
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once('../locallib.php');
require_once ('../classes/FormularioOrientador.class.php');
global $DB, $CFG, $PAGE;
$id = required_param('id', PARAM_INT);
$s  = optional_param('s', 0, PARAM_INT);
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

$event = \mod_sepex\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $sepex);
$event->trigger();
$PAGE->set_url('/mod/sepex/avaliacao_orientador.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();         
echo $OUTPUT->heading(format_string('AVALIAÇÃO DO PROJETO'), 2);
echo $OUTPUT->box(format_string(''), 2);  

//CHAMADA MODEL       
//    if(isset($_GET['data'])){
//        $id_projeto = htmlspecialchars($_GET['data']);
//        $area = htmlspecialchars($_GET['are']);
//        $turno = htmlspecialchars($_GET['tur']);
//        $categoria = htmlspecialchars($_GET['cat']);
//    }
    
    $modcontext    = context_module::instance($cm->id);
    $coursecontext = context_course::instance($course->id);

    
       // $mform = new FormularioOrientador("avaliacao_orientador.php?id={$id}&acao=1&idp={$codProjeto}&cod={$projeto[$codProjeto]->cod_projeto}", array('modcontext'=>$modcontext, 'cod_curso'=>$projeto[$codProjeto]->curso_cod_curso,'titulo' => $projeto[$codProjeto]->titulo, 'resumo' => $projeto[$codProjeto]->resumo, 'tags' => $projeto[$codProjeto]->tags, 'aloca_mesa' => $projeto[$codProjeto]->aloca_mesa, 'cod_periodo' => $projeto[$codProjeto]->cod_periodo, 'turno' => $projeto[$codProjeto]->turno, 'cod_categoria' => $projeto[$codProjeto]->cod_categoria, 'aluno_matricula' => $alunos));
$mform = new FormularioOrientador("avaliacao_orientador.php?id={$id}",array('modcontext'=>$modcontext));

$mform->display(); 
    
        
//    if(isset($_GET['acao'])){
//        if($mform->is_cancelled()):
//            redirect("view.php?id={$id}&are={$area}&tur={$turno}&cat={$categoria}");        
//        elseif ($data = $mform->get_data()):
//            alterar_definicao_projeto($id_projeto, $data->localapresentacao, $data->data_apresentacao);
//            guardar_professor($id_projeto,$data->avaliador,$tipo2);
//            guardar_professor($id_projeto,$data->avaliador2,$tipo2);
//            header("Location: view.php?id={$id}&are={$area}&tur={$turno}&cat={$categoria}");
//        else:
//            header_definicao_projeto($sepex, $cm, $projeto, $orientadores, $id_projeto, $mform);
//        endif;        
//    }else{
//        if($mform->is_cancelled()):
//            redirect("view.php?id={$id}&are={$area}&tur={$turno}&cat={$categoria}");        
//        elseif ($data = $mform->get_data()):           
//            guardar_professor($id_projeto,$data->avaliador,$tipo2);
//            guardar_professor($id_projeto,$data->avaliador2,$tipo2);
//            guardar_definicao_projeto($id_projeto, $data->localapresentacao, $data->data_apresentacao);
//            header("Location: view.php?id={$id}&are={$area}&tur={$turno}&cat={$categoria}");
//        else:
//            header_definicao_projeto($sepex, $cm, $projeto, $orientadores, $id_projeto, $mform);
//        endif;
//    } 




echo $OUTPUT->footer();