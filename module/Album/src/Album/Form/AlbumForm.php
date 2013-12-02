<?php

namespace Album\Form;

use Zend\Form\Form;
use Zend\Form\Element\Select;

class AlbumForm extends Form {

    public function __construct($name = null) {
        parent::__construct('album');

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
            'name' => 'artist',
            'type' => 'Text',
            'options' => array('label' => 'Artist'),
        ));

        $this->add(array(
            'name' => 'lojas_id',
            'type' => 'Select',
            'options' => array(
                'label' => 'Lojas',
            ),
            'attributes' => array(
                'id' => 'lojas_id',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }

}
