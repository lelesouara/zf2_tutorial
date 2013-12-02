<?php

namespace Album\Model;

use Zend\Db\TableGateway\TableGateway;

class AlbumTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    /* Return a RS */

    public function fetchAll() {
        return $this->tableGateway->select();
    }

    public function getAlbum($id) {
        $id = (int) $id;
        $rowSet = $this->tableGateway->select(array('id' => $id));
        $row = $rowSet->current();
        if (!$row)
            throw new \Exception("Cloud not find row $id");
        return $row;
    }
    
    /* Mesclado Editar e salvar (insert) */
    public function saveAlbum(Album $album) {
        $data = array(
            'artist' => $album->artist,
            'title'  => $album->title,
        );
        
        $id = (int) $album->id;
        if ($id == 0){
            $this->tableGateway->insert($data);
        }  else {
            if($this->getAlbum($id)){
                $this->tableGateway->update($data, array('id' => $id));
            }else{
                throw new \Exception ('Album ');
            }
        }
    }
    
    public function deleteAlbum($id){
        
        $this->tableGateway->delete(array('id' => (int) $id));
        
    }

}
