<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Console\Prompt;
use Doctrine\ORM\EntityManager;
use Zend\Session\Container;

class IndexController extends AbstractActionController {

    public function indexAction() {
        return new ViewModel();
    }

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

    public function listlojasAction() {
        
        $request = $this->getRequest();

        $userName = $request->getParam('userName');
        $passwd = $request->getParam('password');

        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('U')
                ->from('Usuario\Entity\Usuario', 'U')
                ->where('U.login = :login AND U.senha = :senha')
                ->setParameter('login', $userName)
                ->setParameter('senha', $passwd);
        $query = $queryBuilder->getQuery();
        $resultOfSearch = $query->getResult();

        if (!empty($resultOfSearch) && count($resultOfSearch) == 1) {
            $queryBuilderLojas = $this->getEntityManager()->createQueryBuilder();
            $queryBuilderLojas->select('L')
                              ->from('Loja\Entity\Loja', 'L')
                              ->orderBy('L.title', 'ASC');
            $queryL = $queryBuilderLojas->getQuery();
            $resultLojas = $queryL->getResult();
            
            $str = "\n\n|-------------------------------------|\n" .
                    "| --------| Listagem de LOJAS |-------|\n";
                    
            foreach($resultLojas as $loja){
                $str .= "\n|Id|-> ".$loja->getId()."\n" ;
                $str .= "\n|Tl|-> ".$loja->getTitle()."\n" ;
                $str .= "\n|Ad|-> ".$loja->getAddress()."\n" ;
                $str .= "----\n\n" ;
            }
            $str .= "\n|-------------------------------------|\n\n";
            return $str;
        } else {

            $str = "\n\n|-------------------------------------|\n" .
                    "| --------| Listagem de LOJAS |-------|\n" .
                    "|@ @ @ @ |-> Usuario incorreto        |" .
                    "\n|-------------------------------------|\n\n";
            return $str;
        }
    }

}
