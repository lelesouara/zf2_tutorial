<?php

namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Entity\Album;
use Album\Form\AlbumForm;
/*
     * Importa a entida gerenciadora do Doctrine.
 */
use Doctrine\ORM\EntityManager;
use Zend\Session\Container;

class AlbumController extends AbstractActionController {
    
    public function init(){
        $session = new Container("base");

        if(!$session->offsetExists("usuario"))
            $this->redirect()->toRoute('usuario', array('action' => 'login'));
    }

    protected $albumTable;
    protected $entityManager;

    public function setEntityManager(EntityManager $em){
        $this->entityManager = $em;
    }


    public function getEntityManager() {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->entityManager;
    }
    
    /*
     * Não usado
    public function getAlbumTable() {
        if (!$this->albumTable) {
            $sm = $this->getServiceLocator();
            $this->albumTable = $sm->get('Album\Model\AlbumTable');
        }
        return $this->albumTable;
    }
    */

    public function indexAction() {
        $this->init();

         $queryBuilder = $this->getEntityManager()->createQueryBuilder();
         $queryBuilder->select('A')
                      ->from('Album\Entity\Album', 'A');
         $query = $queryBuilder->getQuery();
         $result = $query->getResult();
        
        return new ViewModel(array(
            'albums' => $result
        ));
    }
    
    /**
     * Função usada para pegar os dados vindos do banco de dados e transforma-los em
     * um array para ser usando no Select Box.
     * @param Array $arr
     * @return Array
     */
    private function separaLojasValues($arr){
        $arrResultante = array();
        foreach ($arr as $item){
            $arrResultante[$item['id']] = $item['title'];
        }
        return $arrResultante;
    }

    /* Action do controlador (ADICIONAR) */
    public function addAction() {
        $this->init();

        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');
        
        //get datas (lojas) of BD
        $resultSet = $this->getEntityManager()->createQueryBuilder()->select('L.id, L.title')
                                                                    ->from("Loja\Entity\Loja", 'L');
        $resultSet->getQuery()->execute();
        $arrayResultSet = ($resultSet->getQuery()->getArrayResult());
        $arrConvertido = $this->separaLojasValues($arrayResultSet);
        $form->get('lojas_id')->setValueOptions($arrConvertido); 
        $form->get('lojas_id')->setAttributes(array('onchange' => 'ajaxTest()'));
        

        $request = $this->getRequest();
        if ($request->isPost()) {
            $album = new Album();
            $form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());
            
            
            if ($form->isValid()) {
                $album->populate($request->getPost());
                $album->setLojaid($this->getEntityManager()->find('Loja\Entity\Loja', $request->getPost('lojas_id')));
                
                $this->getEntityManager()->persist($album);
                $this->getEntityManager()->flush();
                
                //Redirect to list of albums
                return $this->redirect()->toRoute('album', array('action' => 'index'));
            }else{
                var_dump($form->getMessages());
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $this->init();
        
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album', array('action' => 'add'));
        }

        try {
            $album = $this->getEntityManager()->find('Album\Entity\Album', $id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('album', array('action' => 'index'));
        }

        $form = new AlbumForm();
        $form->setBindOnValidate(false);
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');
        
        //get datas (lojas) of BD
        $resultSet = $this->getEntityManager()->createQueryBuilder()->select('L.id, L.title')
                                                                    ->from("Loja\Entity\Loja", 'L');
        $resultSet->getQuery()->execute();
        $arrayResultSet = ($resultSet->getQuery()->getArrayResult());
        $arrConvertido = $this->separaLojasValues($arrayResultSet);
        $form->get('lojas_id')->setValueOptions($arrConvertido);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $album->setLojaid($this->getEntityManager()->find('Loja\Entity\Loja', $request->getPost('lojas_id')));
                $form->bindValues();
                $this->getEntityManager()->flush();

                //redirect;;
                $this->redirect()->toRoute('album');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction() {
        $this->init();
        
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id)
            return $this->redirect()->toRoute('album');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $album =  $this->getEntityManager()->find('Album\Entity\Album', $id);
                if($album){
                    $this->getEntityManager()->remove($album);
                    $this->getEntityManager()->flush();
                }
            }

            //redirect to page;
            return $this->redirect()->toRoute('album');
        }

        return array(
            'id' => $id,
            'album' => $this->getEntityManager()->find('Album\Entity\Album', $id)
        );
    }

}
