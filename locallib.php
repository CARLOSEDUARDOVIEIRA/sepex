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
//Icons by:
//<div>Icons made by <a href="http://www.flaticon.com/authors/madebyoliver" title="Madebyoliver">Madebyoliver</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
/**
* Biblioteca interna de funções para módulo sepex
*
* Todas as funções específicas do sepex, necessárias para implementar o módulo
* Lógica, deve ir aqui. Nunca inclua este arquivo do seu lib.php!
*
 * @package    mod_sepex
 * @copyright  2017 Carlos Eduardo Vieira <dullvieira@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once('./classes/Projeto.class.php');

defined('MOODLE_INTERNAL') || die();

/**
 * 
 * @global type $DB
 * @param type $dados
 * @return string - Código do projeto(Único).
 */
function criarCodigo($dados){
    global $DB;
    $numero = $DB->count_records('sepex_projeto');
    $categoria = [
    "1" => "EGR",
    "2" => "EST",
    "3" => "INC",
    "4" => "INO",
    "5" => "PE",
    "6" => "PI",
    "7" => "RS",
    "8" => "TL",
    "9" => "TCC",
    ];
    
    if($numero != 0):
        $numero++;      
        $codigo = 'SEP17'.$dados->cod_curso.$categoria[$dados->cod_categoria].'0'.$numero;
    else:
        $codigo = 'SEP17'.$dados->cod_curso.$categoria[$dados->cod_categoria].'01';
    endif;
    
    return $codigo;

}
/**
 * 
 * @global type $DB
 * @param type $dados
 * @param type $id_projeto
 * @return string - Código do projeto atualizado.
 */
function atualizarCodigo($dados, $id_projeto){
    global $DB;    
    $categoria = [
    "1" => "EGR",
    "2" => "EST",
    "3" => "INC",
    "4" => "INO",
    "5" => "PE",
    "6" => "PI",
    "7" => "RS",
    "8" => "TL",
    "9" => "TCC",
    ];
            
        $codigo = 'SEP17'.$dados->cod_curso.$categoria[$dados->cod_categoria].'0'.$id_projeto;
    
    return $codigo;

}
/**
 * 
 * @param type $cod_curso
 * @return int - Retorna qual a área do curso.
 */
function listar_area_curso($cod_curso){
                       
    if($cod_curso == 'ADM'||$cod_curso == 'AUR' || $cod_curso == 'CONT' || $cod_curso == 'TDI' || $cod_curso == 'DIR' || $cod_curso == 'FIL' || $cod_curso == 'PIS' || $cod_curso == 'SES'):
        return 1;

    elseif($cod_curso == 'ENP'||$cod_curso == 'ENC' || $cod_curso == 'SIN' || $cod_curso == 'TADS' || $cod_curso == 'TLO' || $cod_curso == 'RED'):
        return 2;

    elseif($cod_curso == 'CBB'||$cod_curso == 'CBL' || $cod_curso == 'EDF' || $cod_curso == 'ENF' || $cod_curso == 'FTP' || $cod_curso == 'NUT' || $cod_curso == 'FAR'):
        return 3;
    endif;
                
}

/**Metodo responsável por guardar o projeto no banco de dados
 * 
 * @global type $DB
 * @param type $dados
 * @param type $codigo
 * @param type $USER
 */
function guardar_projeto($dados, $codigo, $USER)
{
    global $DB;
   
    $date = new DateTime("now", core_date::get_user_timezone_object());
    $dataAtual = userdate($date->getTimestamp());
    $area = listar_area_curso($dados->cod_curso);
    $projeto = new stdClass();
    $projeto->cod_projeto = $codigo;
    $projeto->titulo = $dados->titulo;
    $projeto->resumo = $dados->resumo[text];
    $projeto->status = null;
    $projeto->data_cadastro = $dataAtual;    
    $projeto->email = $USER->email;
    $projeto->tags = $dados->tags;
    $projeto->cod_periodo = $dados->periodo;
    $projeto->turno = $dados->turno;
    $projeto->area_curso = $area;
    $projeto->aloca_mesa = $dados->aloca_mesa;
    $projeto->cod_categoria = $dados->cod_categoria;
    $id = $DB->insert_record("sepex_projeto", $projeto, $returnid = true );
    
    $curso = new stdClass();
    $curso->curso_cod_curso = $dados->cod_curso;
    $curso->projeto_id_projeto = $id;
    $DB->insert_record("sepex_projeto_curso", $curso);
    
    $aluno = new stdClass();
    $alunos = explode(";",$dados->aluno_matricula);    
    foreach($alunos as $i){
        $aluno->aluno_matricula = $i;
        $aluno->id_projeto = $id;
        $DB->insert_record("sepex_aluno_projeto", $aluno);
    }
    $tipo='orientador';
    guardar_professor($id,$dados->cod_professor,$tipo);
    if($dados->cod_professor2!=0){
        guardar_professor($id,$dados->cod_professor2,$tipo);
    }
}
/**Faz a gravação dos professores dos projetos
 * @global type $DB
 * @param type $id - Id do projeto no qual se deseja atribuir um professor. 
 * @param type $dados
 * @param type $tipo 
 */
function guardar_professor($id,$dados,$tipo){
    global $DB;
    $professor = new stdClass();
    $professor->id_projeto = $id;
    $professor->professor_cod_professor = $dados;
    $professor->tipo = $tipo;    
    $DB->insert_record("sepex_projeto_professor", $professor);    
}

/**
 * Método responsável por atualizar as tabelas de cadastro de resumo sepex
 * @global type $DB
 * @param type $dados
 * @param type $codigo
 * @param type $id_projeto
 */
function atualizar_projeto($dados, $id_projeto)
{
    global $DB;
    
    $date = new DateTime("now", core_date::get_user_timezone_object());
    $dataAtual = userdate($date->getTimestamp());
    $novo_codigo = atualizarCodigo($dados,$id_projeto);
    $area = listar_area_curso($dados->cod_curso);    
    $DB->execute("
            UPDATE mdl_sepex_projeto sp
            INNER JOIN mdl_sepex_projeto_curso pc 
                ON pc.projeto_id_projeto = sp.id_projeto            
                SET sp.cod_projeto = ?,
                sp.titulo = ?, 
                sp.resumo = ?,
                sp.data_cadastro = ?,                
                sp.tags = ?,
                sp.cod_periodo = ?,
                sp.turno = ?,
                sp.area_curso = ?,
                sp.aloca_mesa = ?,
                sp.cod_categoria = ?,
                pc.curso_cod_curso = ?
                WHERE sp.id_projeto = {$id_projeto} ",array($novo_codigo, $dados->titulo, $dados->resumo[text], $dataAtual, $dados->tags, $dados->periodo, $dados->turno, $area, $dados->aloca_mesa, $dados->cod_categoria, $dados->cod_curso ));
    
    $DB->delete_records('sepex_aluno_projeto', array("id_projeto" => $id_projeto));   
    $aluno = new stdClass();
    $alunos = explode(";",$dados->aluno_matricula);    
    foreach($alunos as $i){
        $aluno->aluno_matricula = $i;
        $aluno->id_projeto = $id_projeto;
        $DB->insert_record("sepex_aluno_projeto", $aluno);          
    }
    
    $DB->delete_records('sepex_projeto_professor', array("id_projeto" => $id_projeto));   
    $tipo='orientador';
    guardar_professor($id_projeto,$dados->cod_professor,$tipo);
    if($dados->cod_professor2!=0){
        guardar_professor($id_projeto,$dados->cod_professor2,$tipo);
    }              
}   

/**
 * método responsável por exibir um botão que irá redirecionar para o formulário de inscrição 
 * @return button link
 */
function criar_link_formulario($id){  
    $linkForm  = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:10%;'));
    $linkForm .= html_writer::start_tag('a', array('href'=> 'cadastro_sepex.php?id='.$id, ));
    $linkForm .= html_writer::start_tag('submit',array('class'=>'btn btn-secondary', 'style' => 'margin-bottom:5%;'));
    $linkForm .= get_string('inscricao', 'sepex');
    $linkForm .= html_writer::end_tag('a'); 
    $linkForm .= html_writer::end_tag('div');
    return $linkForm;
}

/**Método responsável por trazer do banco as informações sobre os projetos de um aluno
 * @param type $aluno -> matricula do aluno que deseja listar as informações
 * @return aluno
 */
function select_projetos_aluno($aluno){
 global $DB;     
//Exibir os projetos do aluno
    $resultado = $DB->get_records_sql("
            SELECT
            sp.id_projeto,
            sp.titulo,
            sp.cod_projeto,
            sp.cod_categoria,
            sp.data_cadastro
            FROM mdl_sepex_aluno_projeto sap
            INNER JOIN mdl_sepex_projeto sp ON sp.id_projeto = sap.id_projeto
            WHERE sap.aluno_matricula=?", array($aluno));  
    return $resultado;
}
/**Método responsável por listar os projetos pelo código do professor. 
 * @global type $DB
 * @param type $professor
 * @return type projetos por professor.
 */
function select_projetos_professor($professor){
 global $DB;     
    $resultado = $DB->get_records_sql("
            SELECT
            sp.id_projeto,
            sp.titulo,
            sp.cod_projeto,
            sp.cod_categoria,
            sp.data_cadastro
            FROM mdl_sepex_aluno_projeto sap
            INNER JOIN mdl_sepex_projeto sp ON sp.id_projeto = sap.id_projeto
            WHERE sap.aluno_matricula=?", array($professor));    
    return $resultado;
}

/**Método responsável por obter o código da categoria de um projeto 
 * @global type $DB
 * @param type $cod_categoria
 * @return type categoria do projeto
 */
function retorna_categoria($cod_categoria){
    global $DB; 
    $query = $DB->get_records("sepex_categoria",array("cod_categoria" =>$cod_categoria));
    return $query;
}    


/**
 * Método responsável por criar uma tabela listando os projetos de determinado aluno
 * @param type $usuario = este usuario é o aluno que queremos listar os projetos.
 * @param type $id é o id da página para enviar ao delete_form.php
 * ATENÇÃO -- Na tag table estou usando uma classe do plugin 'forum' para receber tratamentos de css e js,
 * em caso de anomalias na exibição - tente remover essa classe forumheaderlist.
 */
function listar_projetos_aluno($usuario,$id){          
    
    echo criar_link_formulario($id);
       
    $resultado = select_projetos_aluno($usuario);       
    if($resultado !=null || $resultado != ''):
        //Caso o moodle tenha o plugin módulo use o css dele através da classe forumheaderlist
        echo '<table class="forumheaderlist table table-striped">';
            echo '<thead>';
                echo '<tr>';
                    echo '<th>'.get_string('cod_projeto', 'sepex').'</th>';
                    echo '<th>'.get_string('titulo_projeto', 'sepex').'</th>';                    
                    echo '<th>'.get_string('categoria_projeto', 'sepex').'</th>';
                    echo '<th>'.get_string('envio', 'sepex').'</th>';
                    echo '<th>'.get_string('editar', 'sepex').'</th>';
                    echo '<th>'.get_string('apagar', 'sepex').'</th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach($resultado as $projeto){
                echo '<tr>';
                    echo'<td><a>'.$projeto->cod_projeto.'</a></td>';
                    
                    $titulo  = html_writer::start_tag('td');
                    $titulo .= html_writer::start_tag('a', array('href'=> 'cadastro_sepex.php?id='.$id.'&data='.$projeto->id_projeto,));
                    $titulo .= $projeto->titulo;
                    $titulo .= html_writer::end_tag('a'); 
                    $titulo .= html_writer::end_tag('td'); 
                    echo $titulo;
                    
                    $categoria = retorna_categoria($projeto->cod_categoria);
                   
                    echo'<td><a>'.$categoria[$projeto->cod_categoria]->nome_categoria.'</a></td>';
                    
                    echo'<td><a>'.$projeto->data_cadastro.'</a></td>';
                    
                    $editar  = html_writer::start_tag('td');                                       
                    $editar .= html_writer::start_tag('a', array('id'=> 'btnEdit','href'=> 'cadastro_sepex.php?id='.$id.'&data='.$projeto->id_projeto,));
                    $editar .= html_writer::start_tag('img',array('src'=>'pix/edit.png'));
                    $editar .= html_writer::end_tag('a'); 
                    $editar .= html_writer::end_tag('td');
                    echo $editar;
                    
                    $delete  = html_writer::start_tag('td');
                    $delete .= html_writer::start_tag('a', array('href'=> 'acao_form.php?id='.$id.'&proj='.$projeto->id_projeto.'&acao=2', ));
                    $delete .= html_writer::start_tag('img',array('src'=>'pix/delete.png'));
                    $delete .= html_writer::end_tag('a'); 
                    $delete .= html_writer::end_tag('td');
                    echo $delete;
                    
                echo '</tr>';
            }
            echo '</tbody>';
        echo '</table>';
    endif;
    
}
/** Método responsável por apagar um formulário sepex 
 * @global type $DB
 * @param type $id_projeto
 */
function apagar_formulario($id_projeto){
    global $DB;
    $DB->delete_records('sepex_aluno_projeto', array("id_projeto" => $id_projeto));
    $DB->delete_records('sepex_projeto_curso', array("projeto_id_projeto" => $id_projeto));
    $DB->delete_records('sepex_projeto_professor', array("id_projeto" => $id_projeto));
    $DB->delete_records('sepex_projeto', array("id_projeto" => $id_projeto));
}

/**
 * @global type $DB
 * @param type $codProjeto
 * @return type
 */
function listar_projeto_por_id($id_projeto){
    global $DB;     
    //Exibir os projetos do aluno
    $query = $DB->get_records_sql("
            SELECT
            sp.id_projeto,
            sp.cod_projeto,
            sp.titulo,
            sp.resumo,
            sp.email,
            sp.tags,
            sp.cod_periodo,
            sp.turno,
            sp.aloca_mesa,
            sp.cod_categoria,
            spc.curso_cod_curso
            FROM mdl_sepex_projeto sp
            INNER JOIN mdl_sepex_projeto_curso spc ON spc.projeto_id_projeto  = sp.id_projeto
            WHERE sp.id_projeto=?", array($id_projeto));
    return $query;
}
/** Lista os alunos por id de projeto
 * @global type $DB
 * @param type $id_projeto
 * @return type string
 */
function listar_matricula_alunos_por_id_projeto($id_projeto){
    global $DB;         
    $query = $DB->get_records("sepex_aluno_projeto",array("id_projeto" => $id_projeto));        
    $alunos = array();
    foreach($query as $aluno){
            $alunos[$aluno->id_aluno_projeto] =  $aluno->aluno_matricula;
    }     
    $resultado =  implode(";", $alunos);        
    return $resultado;
}
/** Listar codigo dos professores por id projeto.
 * @global type $DB
 * @param type $id_projeto
 * @return type cod_professor
 */
function listar_professor_por_id_projeto($id_projeto){
    global $DB;         
    $query = $DB->get_records("sepex_projeto_professor",array("id_projeto" =>$id_projeto));
       
    $orientadores = array();
    $i = 0;
    foreach($query as $orientador){
        $i++;
          $orientadores[$i] =  $orientador->professor_cod_professor;
    }       
    return $orientadores;
}

function consultar_nome_professor($array_professores){
    global $DB;
       
    $orientadores = array();    
    foreach($array_professores as $orientador){                
        $query = $DB->get_records("sepex_professor",array("cod_professor" =>$orientador));
        array_push($orientadores, $query[$orientador]->nome_professor);                
    }     
    $orientador = implode(", ", $orientadores);
    return $orientador;
}

function exibir_botao_cadastrar_local_apresentacao($id){
    $criarLocalApresentacao  = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:2%;'));
    $criarLocalApresentacao .= html_writer::start_tag('a', array('href'=> 'cadastro_sepex.php?id='.$id, ));
    $criarLocalApresentacao .= html_writer::start_tag('submit',array('class'=>'btn btn-secondary', 'style' => 'margin-bottom:1%;'));
    $criarLocalApresentacao .= get_string('criar_local_apresentacao', 'sepex');
    $criarLocalApresentacao .= html_writer::end_tag('a'); 
    $criarLocalApresentacao .= html_writer::end_tag('div'); 
    echo $criarLocalApresentacao;
}


function viewGerente($id){        

    $localApresentacao  = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:2%;'));
    $localApresentacao .= html_writer::start_tag('a', array('href'=> 'local_apresentacao.php?id='.$id, ));
    $localApresentacao .= html_writer::start_tag('submit',array('class'=>'btn btn-secondary', 'style' => 'margin-bottom:1%;'));
    $localApresentacao .= get_string('definir_local_apresentacao', 'sepex');
    $localApresentacao .= html_writer::end_tag('a'); 
    $localApresentacao .= html_writer::end_tag('div');
    
    $listarProjetos  = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:2%;'));
    $listarProjetos .= html_writer::start_tag('a', array('href'=> 'cadastro_sepex.php?id='.$id, ));
    $listarProjetos .= html_writer::start_tag('submit',array('class'=>'btn btn-secondary', 'style' => 'margin-bottom:1%;'));
    $listarProjetos .= get_string('listar_projetos', 'sepex');
    $listarProjetos .= html_writer::end_tag('a'); 
    $listarProjetos .= html_writer::end_tag('div');
    
    $cadEditProfessor  = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:2%;'));
    $cadEditProfessor .= html_writer::start_tag('a', array('href'=> 'cadastro_sepex.php?id='.$id, ));
    $cadEditProfessor .= html_writer::start_tag('submit',array('class'=>'btn btn-secondary', 'style' => 'margin-bottom:1%;'));
    $cadEditProfessor .= get_string('cad_edit_professor', 'sepex');
    $cadEditProfessor .= html_writer::end_tag('a'); 
    $cadEditProfessor .= html_writer::end_tag('div');    
    echo $localApresentacao;
    echo $listarProjetos;
    echo $cadEditProfessor;
}

function enviar_email($USER){
    global $PAGE, $OUTPUT;
    
    $nao_responda = core_user::get_noreply_user();
    $PAGE->navbar->add('email');
    $titulo_email = 'Titulo do email - Provisório';
    $corpo_email = 'Se você recebeu este email é porque sua tarefa TESTE SEPEX foi enviada com sucesso!';
       
    if(!$resultado = email_to_user($USER, $nao_responda, $titulo_email,$corpo_email)){
        die("Erro no envio do email!");
    }
    
}
/**Exibe os projetos de acordo com um filtro de area, turno, categoria.
 * @global type $DB
 * @param type $dados
 */
function obter_projetos_por_area_turno_categoria($dados){
     global $DB;     
    
    $projeto = $DB->get_records_sql("
            SELECT            
            sp.id_projeto,
            sp.cod_projeto,
            sp.titulo,
            sp.resumo,
            sp.email,
            sp.tags,
            sp.cod_periodo,
            sp.turno,
            sp.aloca_mesa,
            sp.cod_categoria,
            spc.curso_cod_curso            
            FROM mdl_sepex_projeto sp
            INNER JOIN mdl_sepex_projeto_curso spc ON spc.projeto_id_projeto  = sp.id_projeto
            INNER JOIN mdl_sepex_projeto_professor spp ON spp.id_projeto = sp.id_projeto            
            WHERE sp.area_curso=? AND sp.turno =? AND sp.cod_categoria = ?", array($dados->area_curso, $dados->turno, $dados->cod_categoria));
    return $projeto;
}


function listar_projetos_filtrados($projeto,$id){
    $apresentacao = obter_dados_apresentacao($projeto->id_projeto);
        echo '<tbody>';
        echo '<tr>';
            echo'<td><a>'.$projeto->cod_projeto.'</a></td>';
                $titulo  = html_writer::start_tag('td');
                $titulo .= html_writer::start_tag('a', array('href'=> 'cadastro_sepex.php?id='.$id.'&data='.$projeto->id_projeto,));
                $titulo .= $projeto->titulo;
                $titulo .= html_writer::end_tag('a'); 
                $titulo .= html_writer::end_tag('td'); 
            echo $titulo;
                $professor = listar_professor_por_id_projeto($projeto->id_projeto);
                $orientadores = consultar_nome_professor($professor);   
            echo'<td><a>'.$orientadores.'</a></td>';
            echo '<td>'.$apresentacao[$projeto->id_projeto]->nome_local_apresentacao.'</td>';
            echo '<td>'.$apresentacao[$projeto->id_projeto]->data_apresentacao.'</td>';
            echo '<td>'.$apresentacao[$projeto->id_projeto]->hora_apresentacao.'</td>';
                $btnEditar = html_writer::start_tag('td');
                $btnEditar .= html_writer::start_tag('input', array('type'=>'submit', 'id'=> 'editar', 'value'=>get_string('editarsala','sepex'), 'class' => 'btn btn-primary' ));                                                                                                                     
                $btnEditar .= html_writer::end_tag('td');
            echo $btnEditar;
        echo '</tr>';
    echo '</tbody>';
}

function exibir_formulario_definicao_sala($projeto,$id){
    global $DB;
    echo '<tbody>';
        echo '<tr>';
            echo'<td><a>'.$projeto->cod_projeto.'</a></td>';
            $titulo  = html_writer::start_tag('td');
            $titulo .= html_writer::start_tag('a', array('href'=> 'cadastro_sepex.php?id='.$id.'&data='.$projeto->id_projeto,));
            $titulo .= $projeto->titulo;
            $titulo .= html_writer::end_tag('a'); 
            $titulo .= html_writer::end_tag('td'); 
            echo $titulo;         
            $professor = listar_professor_por_id_projeto($projeto->id_projeto);
            $orientadores = consultar_nome_professor($professor);            
            echo'<td><a>'.$orientadores.'</a></td>';
            
            $formulario  = html_writer::start_tag('form', array('id' => 'formularioSepex', 'action'=> "definir_local.php?id={$id}", 'method'=>"post", 'class'=> 'col-lg-9 col-md-9 col-sm-12'));                                                    
                $locais = $DB->get_records('sepex_local_apresentacao');
                $locais_apresentacao = array(''=>'Escolher',);
                foreach($locais as $local){                    
                    $locais_apresentacao[$local->id_local_apresentacao] =  $local->nome_local_apresentacao;
                }
            
                $apresentacao  = html_writer::start_tag('td');                                                     
                $apresentacao .= html_writer::select($locais_apresentacao, 'local_apresentacao', '','', array('class'=> 'col-sm-12'));
                $apresentacao .= html_writer::end_tag('td');


                    $dia  = html_writer::start_tag('td');                               
                        $options = array( 
                            '' => 'Escolher',
                            '2' => 'Segunda feira',
                            '3' => 'Terça feira',
                            '4' => 'Quarta feira',
                            '5' => 'Quinta feira',
                            '6' => 'Sexta feira'                                   
                        );
                        $dia .= html_writer::select($options, 'dia_apresentacao', 0,'', array('class'=> 'col-sm-12'));
                    $dia .= html_writer::end_tag('td');

                    $hora = html_writer::start_tag('td');                               
                        $optionshora = array(                                    
                            '' => 'Escolher',
                            '1' => '18:40 - 20:20',
                            '2' => '20:40 - 22:20'                                                                       
                        );
                        $hora .= html_writer::select($optionshora, 'hora_apresentacao', 0,'', array('class'=> 'col-sm-12'));
                    $hora .= html_writer::end_tag('td'); 

                    $btnSubmit = html_writer::start_tag('td');
                        $btnSubmit .= html_writer::start_tag('input', array('type'=>'submit', 'value'=>get_string('criarsala','sepex'), 'class' => 'btn btn-primary' ));                                                                                                                     
                    $btnSubmit .= html_writer::end_tag('td');
     
                $formulario .= $apresentacao;
                $formulario .= $dia;
                $formulario .= $hora;
                $formulario .= $btnSubmit;
            $formulario .= html_writer::end_tag('form');
            echo $formulario;
        echo '</tr>';
    echo '</tbody>';
}

function projetos_filtrados($dados,$id){   
    global $OUTPUT;    
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
              listar_projetos_filtrados($projeto,$id);                          
            }            
        echo '</table>';                            
    else:            
           echo $OUTPUT->notification(get_string('semprojeto', 'sepex')); 
    endif;    
}
                    
function exibir_formulario_inscricao($sepex,$cm,$mform){
    global $OUTPUT;
    // Primeira exibição do formulário.
        echo $OUTPUT->header();
        //Titulo
        echo $OUTPUT->heading(format_string($sepex->name), 2);
        echo $OUTPUT->box(format_module_intro('sepex', $sepex, $cm->id), 'generalbox', 'intro');
        
        $mform->display(); // exibe o formulário        

        echo $OUTPUT->footer();
}

function obter_dados_apresentacao($id_projeto){
    global $DB;
    $projeto = $DB->get_records_sql("
        SELECT            
            sp.id_projeto,
            sla.nome_local_apresentacao,
            spa.data_apresentacao,
            spa.hora_apresentacao            
            FROM mdl_sepex_projeto sp
            INNER JOIN mdl_sepex_projeto_apresentacao spa ON spa.id_projeto  = sp.id_projeto
            INNER JOIN mdl_sepex_local_apresentacao sla ON sla.id_local_apresentacao = spa.id_local_apresentacao    
            WHERE sp.id_projeto = ?", array($id_projeto));
    return $projeto;
}

function guardar_local_apresentacao($id_projeto, $local,$dia,$hora){
    global $DB;
    
    $projeto = new stdClass();
    $projeto->local_apresentacao = $local;
    $projeto->dia_apresentacao = $dia;
    $projeto->hora_apresentacao = $hora;    
    $DB->insert_record("sepex_projeto", $projeto, array('id_projeto'=>$id_projeto));
    
}