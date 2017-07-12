<?php

require_once ("../../../config.php");
require_once($CFG->dirroot.'/course/moodleform_mod.php');

class FormularioNota extends moodleform {
    function definition(){
        global $DB, $PAGE;

        $mform = $this->_form;
        
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
        $mform->addHelpButton('cod_curso', 'curso', 'sepex');
        
        $area = array(
            '' => 'Escolher',
            '1' => 'Ciências Sociais e Aplicadas',
            '2' => 'Exatas',
            '3' => 'Saúde'            
        );
        $mform->addElement('select', 'area_curso', get_string('area', 'sepex'), $area);       
        $mform->addRule('area_curso', get_string('area', 'sepex'), 'maxlength', 255, 'client');
        $mform->addHelpButton('area_curso', 'area', 'sepex');
                
        $categoria = array(
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
        $mform->addElement('select', 'categoria', get_string('categoria', 'sepex'), $categoria);
        $mform->addRule('categoria', get_string('categoria', 'sepex'), 'maxlength', 255, 'client');
        $mform->addHelpButton('categoria', 'categoria', 'sepex');
        $mform->setDefault('categoria', $this->_customdata['categoria']);

        $turno = array(
            '' => 'Escolher',
            'Matutino' => 'Matutino',
            'Noturno' => 'Noturno',
        );
        $mform->addElement('select', 'turno', get_string('turno', 'sepex'), $turno);        
        $mform->setDefault('turno', $this->_customdata['turno']);
                  
        $nota = array(
            '' => 'Escolher',
            '1' => 'Sim',
            '0' => 'Não',
        );
        $mform->addElement('select', 'nota', format_string('Exibir nota final'), $nota);        
        
        $this->add_action_buttons($cancel = true, $submitlabel = get_string('listarprojetos', 'sepex'));
    }

    function validation($data, $files) {
        return array();
    }
}