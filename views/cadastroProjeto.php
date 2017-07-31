<?php

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require('../classes/Formulario.class.php');
require('../controllers/ProjetoController.class.php');
require ('../locallib.php');

global $DB, $CFG, $PAGE;

$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);
$add = optional_param('add', 0, PARAM_INT);
$update = optional_param('update', 0, PARAM_INT);
$idprojeto = optional_param('idprojeto', 0, PARAM_INT);

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
$modcontext = context_module::instance($cm->id);

$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);

define('VIEW_URL_LINK', "../view.php?id=" . $id);
echo $OUTPUT->header();

$controller = new ProjetoController();

//INSTANCIAÇÃO DO OBJETO FORMULARIO 

if (!empty($add)) {

    $formulario = new Formulario("cadastroProjeto.php?id={$id}&add=1", array('course' => $cm->course));

    if ($formulario->is_cancelled()) {
        redirect(VIEW_URL_LINK);
    } else if ($formulario->get_data()) {
        $controller->save($formulario->get_data());
        enviar_email($USER, $formulario->get_data());
        redirect(VIEW_URL_LINK);
    } else {
        echo $OUTPUT->box(format_module_intro('sepex', $sepex, $cm->id), 'generalbox', 'intro');
        $formulario->display();
    }
} else if (!empty($update)) {
//
//    $projeto = listar_projeto_por_id($id_projeto);
//    $alunos = listar_matricula_alunos_por_id_projeto($id_projeto);
//    $tipo = 'orientador';
//    $professores = listar_professor_por_id_projeto($id_projeto, $tipo);
//
//    //Instanciação de um novo formulario passando como parametro: (destino_formulario, array(informe aqui os campos e os valores dos campos)                
//    $mform = new Formulario("cadastro_sepex.php?id={$id}&update=1&data={$id_projeto}&cod={$projeto[$id_projeto]->cod_projeto}&p={$professores[0]}", array('modcontext' => $modcontext, 'cod_curso' => $projeto[$id_projeto]->curso_cod_curso, 'titulo' => $projeto[$id_projeto]->titulo, 'resumo' => $projeto[$id_projeto]->resumo, 'tags' => $projeto[$id_projeto]->tags, 'aloca_mesa' => $projeto[$id_projeto]->aloca_mesa, 'cod_periodo' => $projeto[$id_projeto]->cod_periodo, 'turno' => $projeto[$id_projeto]->turno, 'cod_categoria' => $projeto[$id_projeto]->cod_categoria, 'aluno_matricula' => $alunos, 'cod_professor' => $professores[0], 'course' => $cm->course));
//
//    if ($dados = $mform->get_data()):
//        atualizar_projeto($dados, $id_projeto, $orientador1, $USER);
//        header("Location:" . VIEW_URL_LINK);
//    else:
//        exibir_formulario_inscricao($sepex, $cm, $mform);
//    endif;
}

echo $OUTPUT->footer();
