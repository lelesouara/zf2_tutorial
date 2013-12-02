<?php

namespace Album\Entity;

use Doctrine\ORM\Mapping as ORM,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\Factory as InputFactory,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface;

/**
 * Album de Musica;
 * Mapeamento da Entidade;
 * 
 * @ORM\Entity
 * @ORM\Table(name = "album")
 * @property string $artist
 * @property string $title
 * @property int $id
 * 
 */
class Album implements InputFilterAwareInterface {

    protected $inputFilter;

    /* Itens que serão mapeados. */

    /**
     * @ORM\Id
     * @ORM\Column(type = "integer")
     * @ORM\ GeneratedValue(strategy = "AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Loja\Entity\Loja")
     * @ORM\JoinColumn(name="loja_id", referencedColumnName="id")
     */
    protected $loja_id;

    /**
     * @ORM\Column(type = "string")
     */
    protected $artist;

    /**
     * @ORM\Column(type = "string")
     */
    protected $title;

    /**
     * 
     * Getters and Setters Methods
     */
    public function getTitle() {
        return $this->title;
    }

    public function getArtist() {
        return $this->artist;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setArtist($artist) {
        $this->artist = $artist;
    }
    
    public function setLojaid($lojaid){
        $this->loja_id = $lojaid;
    }
    
    public function getLojaid(){
        return $this->loja_id;
    }

    /**
     * Converte o objeto em um array
     * @return array
     */
    public function getArrayCopy() {
        return get_object_vars($this);
    }

    /**
     * 
     * @param $data Array
     */
    public function populate($data = array()) {
        $this->id = $data['id'];
        $this->artist = $data['artist'];
        $this->title = $data['title'];
        //$this->loja_id = $data['lojas_id'];
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                        'name' => 'id',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
                    )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'artist',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 1,
                                    'max' => 100,
                                ),
                            ),
                        ),
                    )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'lojas_id',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StringTrim'),
                        ),
                    )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'title',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 1,
                                    'max' => 100,
                                ),
                            ),
                        ),
                    )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
