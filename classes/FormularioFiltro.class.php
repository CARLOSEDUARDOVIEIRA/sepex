<?php

/* Formulário com filtro para definir as salas para cada projeto 
 */

/**
 * Campos de que irão filtar os projetos para serem exibidos na pagina definicaoSala
 *
 * @author Carlos Eduardo Vieira. Linkedin<>.
 */

require($CFG->dirroot . '/course/moodleform_mod.php');
require ('../constantes/Constantes.class.php');

class FormularioFiltro extends moodleform {

    function definition() {

        $mform = $this->_form;
        $constantes = new Constantes();
        
        $areas = $constantes->getAreas();
        $mform->addElement('select', 'areacurso', get_string('area', 'sepex'), $areas);
        $mform->addRule('areacurso', get_string('area', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('areacurso', 'area', 'sepex');
        $mform->setDefault('areacurso', $this->_customdata['areacurso']);
        
        //TURNO        
        $turnos = $constantes->getTurnos();
        $mform->addElement('select', 'turno', get_string('turno', 'sepex'), $turnos);
        $mform->addRule('turno', get_string('turnovazio', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('turno', 'turno', 'sepex');
        $mform->setDefault('turno', $this->_customdata['turno']);
        
        $categorias = $constantes->getCategorias();
        $mform->addElement('select', 'idcategoria', get_string('categoria', 'sepex'), $categorias);
        $mform->addRule('idcategoria', get_string('categoriavazio', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('idcategoria', 'categoria', 'sepex');
        $mform->setDefault('idcategoria', $this->_customdata['idcategoria']);
        
        $this->add_action_buttons($cancel = true, $submitlabel = get_string('listarprojetos', 'sepex'));
    }

    function validation($data, $files) {
        return array();
    }

}
