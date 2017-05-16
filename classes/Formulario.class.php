<?php

/**
 * Formulario de inscrição sepex, aqui são definidos os campos necessários para o cadastro dos projetos.
 *
 * @author Carlos Eduardo Vieira. Linkedin<>.
 */

<<<<<<< HEAD
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
=======
require_once ("../../config.php");
>>>>>>> 8343b778cd04ab99ad3b0b60342a6df9f3bf18db
require_once($CFG->dirroot.'/course/moodleform_mod.php');


class Formulario extends moodleform {
    
    function definition() {
        global $DB, $PAGE;
        
<<<<<<< HEAD
        $mform = $this->_form;         
=======
        $mform = $this->_form; 
        $coursecontext = $this->_customdata['coursecontext'];
>>>>>>> 8343b778cd04ab99ad3b0b60342a6df9f3bf18db
        $modcontext = $this->_customdata['modcontext'];
        
        //CURSO
        $cursos = array(
            '' => 'Escolher',
            'ADM' => 'Administração',
            'AUR' => 'Arquitetura e Urbanismo',
            'CBB' => 'Ciências Biológicas (Bacharelado)',
            'CBL' => 'Ciências Biológicas (Licenciatura)',
            'CONT' => 'Ciências Contábeis',
            'TDI' => 'Tecnologia em design de interiores',
            'DIR' => 'Direito',
            'EDF' => 'Educação Física',
            'ENF' => 'Enfermagem',
            'ENC' => 'Engenharia Civil',
            'ENP' => 'Engenharia de Produção',
            'FAR' => 'Farmácia',
            'FIL' => 'Filosofia',
            'FTP' => 'Fisioterapia',
            'NUT' => 'Nutrição',
            'PIS' => 'Psicologia',
            'SES' => 'Serviço Social',
            'SIN' => 'Sistemas de Informação',
            'TADS' => 'Tecnologia em Análise e Desenvolvimento de Sistemas',
            'TLO' => 'Tecnologia em Logística',
            'RED' => 'Tecnologia em Redes de Computadores'
        );
        $mform->addElement('select', 'cod_curso', get_string('curso', 'sepex'), $cursos);
        $mform->addRule('cod_curso', get_string('cursovazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('cod_curso', get_string('curso', 'sepex'), 'maxlength', 255, 'client');
<<<<<<< HEAD
        $mform->addHelpButton('cod_curso', 'curso', 'sepex');
=======
        $mform->addHelpButton('cod_curso', 'curso_help', 'sepex');
>>>>>>> 8343b778cd04ab99ad3b0b60342a6df9f3bf18db
        $mform->setDefault('cod_curso',$this->_customdata['cod_curso']);
        
        //PERIODO
        $periodos = array(
            '' => 'Escolher',
            '1' => 'Primeiro Período',
            '2' => 'Segundo Período',
            '3' => 'Terceiro Período',
            '4' => 'Quarto Período',
            '5' => 'Quinto Período',
            '6' => 'Sexto Período',
            '7' => 'Sétimo Período',
            '8' => 'Oitavo Período',
            '9' => 'Nono Período',
            '10' => 'Décimo Período',
        );
        $mform->addElement('select', 'periodo', get_string('periodo', 'sepex'), $periodos);
        $mform->addRule('periodo', get_string('periodovazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('periodo', get_string('periodo', 'sepex', 255), 'maxlength', 255, 'client');
<<<<<<< HEAD
        $mform->addHelpButton('periodo', 'periodo', 'sepex');
=======
        $mform->addHelpButton('periodo', 'periodo_help', 'sepex');
>>>>>>> 8343b778cd04ab99ad3b0b60342a6df9f3bf18db
        $mform->setDefault('periodo',$this->_customdata['cod_periodo']);
        
        //TURNO
         $turnos = array(
            '' => 'Escolher',
            'Matutino' => 'Matutino',
            'Noturno' => 'Noturno',
        );
        $mform->addElement('select', 'turno', get_string('turno', 'sepex'), $turnos);
        $mform->addRule('turno', get_string('turnovazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('turno', get_string('turno', 'sepex', 255), 'maxlength', 255, 'client');
<<<<<<< HEAD
        $mform->addHelpButton('turno', 'turno', 'sepex');
=======
        $mform->addHelpButton('turno', 'turno_help', 'sepex');
>>>>>>> 8343b778cd04ab99ad3b0b60342a6df9f3bf18db
        $mform->setDefault('turno',$this->_customdata['turno']);
        
        //CATEGORIA
        $categorias = array(
            '' => 'Escolher',
            '1' => 'Egressos',
            '2' => 'Estágios',
            '3' => 'Iniciação Científica',
            '4' => 'Inovação',
            '5' => 'Projeto de Extensão',
            '6' => 'Projeto Integrador',
            '7' => 'Responsábilidade Social',
            '8' => 'Temas Livres',
            '9' => 'Trabalho de Conclusão de Curso'
        ); 
        
        $mform->addElement('select', 'cod_categoria', get_string('categoria', 'sepex'), $categorias);
        $mform->addRule('cod_categoria',get_string('categoriavazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('cod_categoria', get_string('categoria', 'sepex', 255), 'maxlength', 255, 'client');
<<<<<<< HEAD
        $mform->addHelpButton('cod_categoria', 'categoria', 'sepex');
=======
        $mform->addHelpButton('cod_categoria', 'categoria_help', 'sepex');
>>>>>>> 8343b778cd04ab99ad3b0b60342a6df9f3bf18db
        $mform->setDefault('cod_categoria',$this->_customdata['cod_categoria']);
              
        //TITULO DO TRABALHO
        $mform->addElement('text', 'titulo', get_string('titulo', 'sepex'), array('size' => '64'));
<<<<<<< HEAD
        $mform->setType('titulo', PARAM_RAW); 
        $mform->addRule('titulo', get_string('titulovazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('titulo', get_string('titulo', 'sepex', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('titulo', 'titulo', 'sepex');
=======
        $mform->addRule('titulo', get_string('titulovazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('titulo', get_string('titulo', 'sepex', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('titulo', 'titulo_help', 'sepex');
>>>>>>> 8343b778cd04ab99ad3b0b60342a6df9f3bf18db
        $mform->setDefault('titulo',$this->_customdata['titulo']);
        
        //MATRICULA DO ALUNO
        $mform->addElement('text', 'aluno_matricula', get_string('integrantes', 'sepex'), array('size' => '64'));
<<<<<<< HEAD
        $mform->setType('aluno_matricula', PARAM_RAW);
        $mform->addRule('aluno_matricula', get_string('integrantevazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('aluno_matricula', get_string('integrantes', 'sepex', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('aluno_matricula', 'integrantes', 'sepex');
=======
        $mform->addRule('aluno_matricula', get_string('integrantevazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('aluno_matricula', get_string('integrantes', '', 255), 'integrantes', 255, 'client');
        $mform->addHelpButton('aluno_matricula', 'integrantes_help', 'sepex');
>>>>>>> 8343b778cd04ab99ad3b0b60342a6df9f3bf18db
        $mform->setDefault('aluno_matricula',$this->_customdata['aluno_matricula']);
              
        //ORIENTADOR        
        $orientadores = $DB->get_records('sepex_professor');
        $professores = array(''=>'Escolher',);
        foreach($orientadores as $professor){
            $professores[$professor->cod_professor] =  $professor->nome_professor;
        }
        
        $mform->addElement('select', 'cod_professor', get_string('orientador', 'sepex'), $professores);
        $mform->addElement('select', 'cod_professor2', get_string('orientador2', 'sepex'), $professores);
        $mform->addRule('cod_professor', get_string('orientadorvazio', 'sepex'), 'required', null, 'client');
<<<<<<< HEAD
        $mform->addHelpButton('cod_professor', 'orientador', 'sepex');
=======
        $mform->addHelpButton('cod_professor', 'orientador_help', 'sepex');
>>>>>>> 8343b778cd04ab99ad3b0b60342a6df9f3bf18db
        $mform->setDefault('cod_professor',$this->_customdata['cod_professor']);
        $mform->setDefault('cod_professor2',$this->_customdata['cod_professor2']);
        
        //RESUMO        
        $resumo = $this->_customdata['resumo'];
        $mform->addElement('editor', 'resumo', get_string('resumo', 'sepex'), null, array('context' => $modcontext))->setValue( array('text' => $resumo));        
        $mform->addRule('resumo', get_string('resumovazio', 'sepex'), 'required', null, 'client');
<<<<<<< HEAD
        $mform->addHelpButton('resumo', 'resumo', 'sepex');
=======
        $mform->addHelpButton('resumo', 'resumo_help', 'sepex');
>>>>>>> 8343b778cd04ab99ad3b0b60342a6df9f3bf18db
        $mform->setType('resumo', PARAM_RAW);
        
        //TAGS       
        $mform->addElement('text', 'tags', get_string('tags', 'sepex'), array('size' => '64'));
<<<<<<< HEAD
        $mform->setType('tags', PARAM_RAW);
        $mform->addRule('tags', get_string('tagsvazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('tags', get_string('tags', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('tags', 'tags', 'sepex');
=======
        $mform->addRule('tags', get_string('tagsvazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('tags', get_string('tags', '', 255), 'tags', 255, 'client');
        $mform->addHelpButton('tags', 'tags_help', 'sepex');
>>>>>>> 8343b778cd04ab99ad3b0b60342a6df9f3bf18db
        $mform->setDefault('tags',$this->_customdata['tags']);
       
        //ALOCA MESA
        $mesa = array(
            '' => 'Escolher',
            '1' => 'Eu desejo uma mesa',
            '0' => 'Não desejo uma mesa',
        );
        $mform->addElement('select', 'aloca_mesa', get_string('alocamesa', 'sepex'), $mesa);
        $mform->addRule('aloca_mesa', get_string('alocamesavazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('aloca_mesa', get_string('alocamesa', 'sepex', 255), 'maxlength', 255, 'client');
<<<<<<< HEAD
        $mform->addHelpButton('aloca_mesa', 'alocamesa', 'sepex');
=======
        $mform->addHelpButton('aloca_mesa', 'alocamesa_help', 'sepex');
>>>>>>> 8343b778cd04ab99ad3b0b60342a6df9f3bf18db
        $mform->setDefault('aloca_mesa',$this->_customdata['aloca_mesa']);
        
        
        
        $mform->addElement('submit', 'btnEnviar', get_string("btnEnviar", 'sepex'));
        
        
    }
    function validation($data, $files) {
        return array();
    }
}
