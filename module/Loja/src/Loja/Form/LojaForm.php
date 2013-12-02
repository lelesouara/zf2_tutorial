<?php
namespace Loja\Form;

use Zend\Form\Form;

class LojaForm extends Form{
    
    public function __construct($name = null){
        parent::__construct('loja');
        
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));
        
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array('label' => 'Title'),
        ));
        
        $this->add(array(
            'name' => 'address',
            'type' => 'Textarea',
            'options' => array('label' => 'Address'),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton'
            ),
        ));
    }
    
}