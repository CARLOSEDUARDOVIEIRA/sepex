<?php

/**
 * Formulario de inscrição sepex, aqui são definidos os campos necessários para o cadastro dos projetos.
 *
 * @author Carlos Eduardo Vieira. Linkedin<>.
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once($CFG->dirroot.'/course/moodleform_mod.php');

class Formulario extends moodleform {
    
    function definition() {
        global $DB, $PAGE;           
        $mform = $this->_form;         
        $course = $this->_customdata['course'];
        $modcontext = $this->_customdata['modcontext'];
        
        if(isset($this->_customdata['modcontext'])){            
            $mform->setDefault('cod_curso',$this->_customdata['cod_curso']);    
            $mform->setDefault('periodo',$this->_customdata['periodo']);
            $mform->setDefault('turno',$this->_customdata['turno']);
            $mform->setDefault('cod_categoria',$this->_customdata['cod_categoria']);
            $mform->setDefault('titulo',$this->_customdata['titulo']);
            $mform->setDefault('aluno_matricula',$this->_customdata['aluno_matricula']);
            $resumo = $this->_customdata['resumo'];
            $mform->setDefault('tags',$this->_customdata['tags']);
            $mform->setDefault('aloca_mesa',$this->_customdata['aloca_mesa']);
            $mform->setDefault('cod_professor',$this->_customdata['cod_professor']);
        }
        
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
        $mform->addHelpButton('cod_curso', 'curso', 'sepex');
        
        
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
        $mform->addHelpButton('periodo', 'periodo', 'sepex');
        
        
        //TURNO
         $turnos = array(
            '' => 'Escolher',
            'Matutino' => 'Matutino',
            'Noturno' => 'Noturno',
        );
        $mform->addElement('select', 'turno', get_string('turno', 'sepex'), $turnos);
        $mform->addRule('turno', get_string('turnovazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('turno', get_string('turno', 'sepex', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('turno', 'turno', 'sepex');
        
        
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
            '9' => 'Trabalho de Conclusão de Curso',
            '10' => 'Mostra de Vídeos',
            '11' => 'Concurso de Fotografia'
        ); 
        
        $mform->addElement('select', 'cod_categoria', get_string('categoria', 'sepex'), $categorias);
        $mform->addRule('cod_categoria',get_string('categoriavazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('cod_categoria', get_string('categoria', 'sepex', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('cod_categoria', 'categoria', 'sepex');
        
              
        //TITULO DO TRABALHO
        $mform->addElement('text', 'titulo', get_string('titulo', 'sepex'), array('placeholder'=> 'Meu Título de Projeto SEPEX 2017','size' => '64'));
        $mform->setType('titulo', PARAM_RAW); 
        $mform->addRule('titulo', get_string('titulovazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('titulo', get_string('titulo', 'sepex', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('titulo', 'titulo', 'sepex');
        
        
        //MATRICULA DO ALUNO                           
        $mform->addElement('text', 'aluno_matricula', get_string('integrantes', 'sepex'), array('placeholder'=> ' 6914001025;6914001026;6914001027', 'size' => '64'));
        $mform->setType('aluno_matricula', PARAM_RAW);
        $mform->addRule('aluno_matricula', get_string('integrantevazio', 'sepex'), 'required', null, 'client');        
        $mform->addHelpButton('aluno_matricula', 'integrantes', 'sepex');                     
              
        //ORIENTADOR
        $teacher = 'editingteacher';
        $orientadores = listar_usuarios_por_curso($teacher,$course);        
        $professores = array(''=>'Escolher',);
        foreach($orientadores as $professor){
            $professores[$professor->username] =  $professor->name;
        }
     
        $mform->addElement('select', 'cod_professor', get_string('orientador', 'sepex'), $professores);        
        $mform->addRule('cod_professor', get_string('orientadorvazio', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('cod_professor', 'orientador', 'sepex');
        
        //RESUMO                
        $mform->addElement('editor', 'resumo', get_string('resumo', 'sepex'), null, array('context' => $modcontext))->setValue( array('text' => $resumo));        
        $mform->addRule('resumo', get_string('resumovazio', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('resumo', 'resumo', 'sepex');
        $mform->setType('resumo', PARAM_RAW);
        
        //TAGS       
        $mform->addElement('text', 'tags', get_string('tags', 'sepex'), array('placeholder'=> ' Palavra1;Palavra2;Palavra3','size' => '64'));
        $mform->setType('tags', PARAM_RAW);
        $mform->addRule('tags', get_string('tagsvazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('tags', get_string('tags', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('tags', 'tags', 'sepex');
        
       
        //ALOCA MESA
        $mesa = array(
            '' => 'Escolher',
            '1' => 'Eu desejo uma mesa',
            '0' => 'Não desejo uma mesa',
        );
        $mform->addElement('select', 'aloca_mesa', get_string('alocamesa', 'sepex'), $mesa);
        $mform->addRule('aloca_mesa', get_string('alocamesavazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('aloca_mesa', get_string('alocamesa', 'sepex', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('aloca_mesa', 'alocamesa', 'sepex');
        
        
        
        
        $mform->addElement('submit', 'btnEnviar', get_string("btnEnviar", 'sepex'));
        
        
    }
    function validation($data, $files) {
        return array();
    }
}
