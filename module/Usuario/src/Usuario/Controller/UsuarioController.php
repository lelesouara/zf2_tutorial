<?php

namespace Usuario\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Usuario\Form\UsuarioForm as UsuarioForm;
use Doctrine\ORM\EntityManager;
use Usuario\Entity\Usuario;
use Zend\Session\Container;
//TEST
use Zend\View\Model\JsonModel;

class UsuarioController extends AbstractActionController {

    protected $entityManager;

    public function setEntityManager(EntityManager $em) {
        $this->entityManager = $em;
    }

    public function getEntityManager() {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->entityManager;
    }

    public function init() {
        $session = new Container("base");

        if (!$session->offsetExists("usuario"))
            $this->redirect()->toRoute('usuario', array('action' => 'login'));
    }

    public function loginAction() {
        $form = new UsuarioForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $UsuarioEntity = new Usuario();
            $form->setInputFilter($UsuarioEntity->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $paramsForSearchUser = array(
                    'login' => $request->getPost('login'),
                    'senha' => $request->getPost('senha')
                );

                $queryBuilder = $this->getEntityManager()->createQueryBuilder();
                $queryBuilder->select('U')
                        ->from('Usuario\Entity\Usuario', 'U')
                        ->where('U.login = :login AND U.senha = :senha')
                        ->setParameter('login', $paramsForSearchUser['login'])
                        ->setParameter('senha', $paramsForSearchUser['senha'])
                        ->setMaxResults(1);

                $query = $queryBuilder->getQuery();
                $result = $query->getResult();

                //$result = $this->getEntityManager()->getRepository('Usuario\Entity\Usuario')->findBy($paramsForSearchUser);

                if (is_array($result) && (count($result) == 1)) {
                    $session = new Container("base");
                    $session->usuario = $result[0];

                    $this->redirect()->toRoute('usuario', array('action' => 'welcome'));
                } else {
                    $this->redirect()->toRoute('usuario', array('action' => 'login'));
                }
            }
        }
        return array(
            'form' => $form,
        );
    }

    public function logoutAction() {
        $session = new Container("base");
        $session->offsetUnset("usuario");
        $this->redirect()->toRoute('usuario', array('action' => 'login'));
    }

    public function welcomeAction() {
        $this->init();

        $session = new Container("base");
    }

    public function sessiondataAction() {
        $this->init();

        $session = new Container("base");
        if ($session->offsetExists("usuario")) {
            if ($this->getRequest()->isPost()) {
                $id = $this->getRequest()->getPost('idLoja');
                
                //Consulta
                $queryBuilder = $this->getEntityManager()->createQueryBuilder();
                $queryBuilder->select('L')
                             ->from('Loja\Entity\Loja', 'L')
                             ->where("L.id = :id")
                             ->setParameter('id', $id);
                $query = $queryBuilder->getQuery();
                $result = $query->getResult();
                
                return new JsonModel($result[0]->getArrayCopy());
            } else {
                return null;
            }
        } else {
            return $this->redirect()->toRoute('usuario');
        }
    }

}