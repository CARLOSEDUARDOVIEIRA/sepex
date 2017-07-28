<?php

/* PÁGINA DE EXIBIÇÃO DO FORMULÁRIO DE CADASTRO SEPEX
 */

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
////require_once('../locallib.php');
//require_once('../classes/Formulario.class.php');
require('../controllers/ProjetoController.class.php');

global $DB, $CFG, $PAGE;

//$id = required_param('id', PARAM_INT); 
//$s  = optional_param('s', 0, PARAM_INT); 
//$add    = optional_param('add',0, PARAM_INT);
//$update = optional_param('update',0, PARAM_INT);
//$id_projeto  = optional_param('data',0, PARAM_INT);
//$orientador1  = optional_param('p',0,PARAM_INT);
//
//if ($id) {
//    $cm         = get_coursemodule_from_id('sepex', $id, 0, false, MUST_EXIST);
//    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
//    $sepex  = $DB->get_record('sepex', array('id' => $cm->instance), '*', MUST_EXIST);
//} else if ($s) {
//    $sepex  = $DB->get_record('sepex', array('id' => $s), '*', MUST_EXIST);
//    $course     = $DB->get_record('course', array('id' => $sepex->course), '*', MUST_EXIST);
//    $cm         = get_coursemodule_from_instance('sepex', $sepex->id, $course->id, false, MUST_EXIST);
//} else {
//    error('Você deve especificar um course_module ID ou um ID de instância');
//}
//
//$lang = current_language();
//require_login($course, true, $cm);
//$modcontext    = context_module::instance($cm->id);
//
//// DEFININDO LINK PARA PÁGINA DO USUÁRIO.
//define('FORMULARIO_LINK', "cadastro_sepex.php?id=".$id);
//
//$PAGE->set_title(format_string($sepex->name));
//$PAGE->set_heading($course->fullname);
//
//define('VIEW_URL_LINK', "../view.php?id=" . $id);
//
$dados = new stdClass();
$dados->idcategoria = optional_param('idcategoria', 0, PARAM_INT);
$dados->titulo = optional_param('titulo', null, PARAM_RAW);
$dados->resumo = optional_param('resumo', null, PARAM_RAW);
$dados->tags = optional_param('tags', null, PARAM_RAW);
$dados->idperiodo = optional_param('idperiodo', 0, PARAM_INT);
$dados->turno = optional_param('turno', null, PARAM_RAW);
$dados->idcurso = optional_param('idcurso',null, PARAM_RAW);
$dados->alocamesa = optional_param('alocamesa', 0, PARAM_INT);
$dados->matraluno = "6914104289";
$dados->matrprofessor = "6914102";
$dados->idprojeto = 8;

$controller = new ProjetoController();
$result = $controller->detail($dados->idprojeto);

header('Content-Type: application/json; charset=utf-8');
$json = file_get_contents('php://input');
echo json_encode($result);


//    //INSTANCIAÇÃO DO OBJETO FORMULÁRIO - Obtendo o id do projeto via get
//    if (!empty($add)) {
//        $mform = new Formulario("../controllers/ProjetoController.php?id={$id}&acao=1", array('course'=> $cm->course));        
//         
//        if($dados = $mform->get_data()):        
//            $codigo = criarCodigo($dados);
//            guardar_projeto($dados,$codigo,$USER);
//            enviar_email($USER, $dados);
//            header("Location:". VIEW_URL_LINK);
//        else:     
//            exibir_formulario_inscricao($sepex,$cm,$mform);
//        endif;
//
//    } else if (!empty($update)) {
//        
//        $projeto = listar_projeto_por_id($id_projeto);
//        $alunos = listar_matricula_alunos_por_id_projeto($id_projeto);
//        $tipo = 'orientador';
//        $professores = listar_professor_por_id_projeto($id_projeto, $tipo);
//        
//        //Instanciação de um novo formulario passando como parametro: (destino_formulario, array(informe aqui os campos e os valores dos campos)                
//        $mform = new Formulario("cadastro_sepex.php?id={$id}&update=1&data={$id_projeto}&cod={$projeto[$id_projeto]->cod_projeto}&p={$professores[0]}", array('modcontext'=>$modcontext, 'cod_curso'=>$projeto[$id_projeto]->curso_cod_curso,'titulo' => $projeto[$id_projeto]->titulo, 'resumo' => $projeto[$id_projeto]->resumo, 'tags' => $projeto[$id_projeto]->tags, 'aloca_mesa' => $projeto[$id_projeto]->aloca_mesa, 'periodo' => $projeto[$id_projeto]->periodo, 'turno' => $projeto[$id_projeto]->turno, 'cod_categoria' => $projeto[$id_projeto]->cod_categoria, 'aluno_matricula' => $alunos, 'cod_professor'=> $professores[0],'course'=> $cm->course));        
//        
//        if($dados = $mform->get_data()):        
//            atualizar_projeto($dados,$id_projeto, $orientador1, $USER);
//            header("Location:". VIEW_URL_LINK);            
//        else:
//            exibir_formulario_inscricao($sepex,$cm,$mform);
//        endif;        
//    }


 