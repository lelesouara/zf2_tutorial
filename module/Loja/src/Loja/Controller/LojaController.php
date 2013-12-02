<?php

namespace Loja\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Loja\Form\LojaForm;
use Doctrine\ORM\EntityManager;
use Loja\Entity\Loja;

use Zend\Session\Container;

class LojaController extends AbstractActionController {

    protected $entityManager;

    public function setEntityManager(EntityManaget $em) {
        $this->entityManager = $em;
    }

    public function getEntityManager() {
        if ($this->entityManager === null) {
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->entityManager;
    }
    
    public function init(){
        $session = new Container("base");
        
        if(!$session->offsetExists("usuario"))
            $this->redirect()->toRoute('usuario', array('action' => 'login'));
    }

    public function indexAction() {
        $this->init();
        
        return new ViewModel(array(
                    'lojas' => $this->getEntityManager()->getRepository('Loja\Entity\Loja')->findAll()
                ));
    }

    public function addAction() {
        $this->init();
        
        /* Cria o Formulario com Zend_Form */
        $formRender = new LojaForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $LojaEntity = new Loja();
            $formRender->setInputFilter($LojaEntity->getInputFilter());
            $formRender->setData($request->getPost());
            if ($formRender->isValid()) {
                $LojaEntity->populate($formRender->getData());
                $this->getEntityManager()->persist($LojaEntity);
                $this->getEntityManager()->flush();

                $this->redirect()->toRoute('loja');
            }
        }
        return array('form' => $formRender);
    }

    public function editAction() {
        $this->init();
        
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('loja', array('action' => 'add'));
        }

        try {
            $loja = $this->getEntityManager()->find('Loja\Entity\Loja', $id);
        } catch (\Exception $exc) {
            return $this->redirect()->toRoute('loja', array('action' => 'index'));
        }

        $form = new LojaForm();
        $form->setBindOnValidate(false);
        $form->bind($loja);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $form->bindValues();
                $this->getEntityManager()->flush();

                $this->redirect()->toRoute('loja');
            }
        }
        return array(
            'form' => $form,
            'id' => $id,
        );
    }

    public function deleteAction() {
        $this->init();
        
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('loja', array('action' => 'index'));
        }

        $request = $this->getRequest();
        if($request->isPost()){
            $del = $request->getPost('del', 'No');
            
            if($del == 'Yes'){
                $id = (int) $request->getPost('id');

                $loja = $this->getEntityManager()->find('Loja\Entity\Loja', $id);
                if($loja){
                    $this->getEntityManager()->remove($loja);
                    $this->getEntityManager()->flush();
                }
            }
            return $this->redirect()->toRoute('loja');
        }
        return array(
            'id' => $id,
            'loja' => $this->getEntityManager()->find('Loja\Entity\Loja', $id),
        );
    }

}
