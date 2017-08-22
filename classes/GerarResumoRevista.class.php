<?php

/**
 * Description of GerarResumoRevista
 *
 * @author Carlos Eduardo Vieira
 */

require($CFG->dirroot . '/course/moodleform_mod.php');
require ('../constantes/Constantes.class.php');

class GerarResumoRevista extends moodleform {

    function definition() {
        
        $constantes = new Constantes();
        $mform = $this->_form;
        
        //CATEGORIA
        $categorias = $constantes->getCategorias();
        $mform->addElement('select', 'idcategoria', get_string('categoria', 'sepex'), $categorias);
        $mform->addRule('idcategoria', get_string('categoriavazio', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('idcategoria', 'categoria', 'sepex');
        
        $this->add_action_buttons($cancel = true, get_string('btnEnviar', 'sepex'));
    }
    
}
