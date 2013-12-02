<?php

namespace Usuario\Form;

use Zend\Form\Form;

class UsuarioForm extends Form {

    public function __construct($name = null) {
        parent::__construct('usuario');

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));

        $this->add(array(
            'name' => 'login',
            'type' => 'Text',
            'options' => array('label' => 'Login'),
        ));

        $this->add(array(
            'name' => 'senha',
            'type' => 'Password',
            'options' => array('label' => 'Senha'),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Logar',
                'id' => 'submitbutton'
            ),
        ));
    }

}