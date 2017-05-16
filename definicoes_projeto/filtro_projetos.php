<?php

/* EXIBE O FILTRO DE PROJETOS PARA O GERENTE DEFINIR O LOCAL DE APRESENTAÇÃO DOS PROJETOS FILTRADOS
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once('../locallib.php');
require_once('../classes/FormularioFiltro.class.php');
global $DB, $PAGE;

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

$PAGE->set_url('/mod/sepex/filtro_projetos.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);

    echo $OUTPUT->header();        
    echo $OUTPUT->heading(format_string('Listagem dos projetos filtrados'), 2);
    echo $OUTPUT->box(format_module_intro('sepex', $sepex, $cm->id), 'generalbox', 'intro');

    $mform = new FormularioFiltro();
    $dados = $mform->get_data(); 
    //VIEW
        $projetos = obter_projetos_por_area_turno_categoria($dados);
        
        if($projetos):        
            echo '<table class="forumheaderlist table table-striped">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>'.get_string('cod_projeto', 'sepex').'</th>';
                        echo '<th>'.get_string('titulo_projeto', 'sepex').'</th>';                    
                        echo '<th>'.get_string('orientadores', 'sepex').'</th>';
                        echo '<th>'.get_string('localapresentacao', 'sepex').'</th>';
                        echo '<th>'.get_string('dia', 'sepex').'</th>';
                        echo '<th>'.get_string('horario', 'sepex').'</th>';
                        echo '<th>'.'</th>';
                    echo '</tr>';
                echo '</thead>';                
                foreach($projetos as $projeto){
                    $apresentacao = obter_dados_apresentacao($projeto->id_projeto);
                    echo '<tbody>';
                        echo '<tr>';
                            echo'<td><a>'.$projeto->cod_projeto.'</a></td>';
                                $titulo  = html_writer::start_tag('td');
                                $titulo .= html_writer::start_tag('a', array('href'=> 'definicao_projeto.php?id='.$id.'&data='.$projeto->id_projeto,));
                                $titulo .= $projeto->titulo;
                                $titulo .= html_writer::end_tag('a'); 
                                $titulo .= html_writer::end_tag('td'); 
                            echo $titulo;
                                $professor = listar_professor_por_id_projeto($projeto->id_projeto);
                                $orientadores = consultar_nome_professor($professor);   
                            echo'<td><a>'.$orientadores.'</a></td>';
                            if (isset($apresentacao[$projeto->id_projeto]->nome_local_apresentacao)){
                                echo '<td>'.$apresentacao[$projeto->id_projeto]->nome_local_apresentacao.'</td>';
                                echo '<td>'.$apresentacao[$projeto->id_projeto]->data_apresentacao.'</td>';
                                echo '<td>'.$apresentacao[$projeto->id_projeto]->hora_apresentacao.'</td>';
                            }else{
                                echo '<td>'.'</td>';
                                echo '<td>'.'</td>';
                                echo '<td>'.'</td>';
                            }
                                $btnEditar = html_writer::start_tag('td');
                                $btnEditar .= html_writer::start_tag('a', array('href'=> 'definicao_projeto.php?id='.$id.'&data='.$projeto->id_projeto,));
                                $btnEditar .= html_writer::start_tag('input', array('type'=>'button', 'id'=> 'editar', 'value'=>get_string('editar','sepex'), 'class' => 'btn btn-default' ));                                                                                                                     
                                $btnEditar .= html_writer::end_tag('td');
                                echo $btnEditar;
                        echo '</tr>';
                    echo '</tbody>';                        
                }            
            echo '</table>';                            
        else:            
               echo $OUTPUT->notification(get_string('semprojeto', 'sepex')); 
        endif; 
    
    if(isset($_POST['local_apresentacao'])){
        $local = htmlspecialchars($_POST['local_apresentacao']);
        $dia = htmlspecialchars($_POST['dia_apresentacao']);
        $hora = htmlspecialchars($_POST['hora_apresentacao']);                
        guardar_local_apresentacao($id_projeto,$local,$dia,$hora);    
    }
    
    echo $OUTPUT->footer();

