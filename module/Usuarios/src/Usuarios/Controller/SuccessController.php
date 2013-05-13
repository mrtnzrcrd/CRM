<?php

namespace Usuarios\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
 
class SuccessController extends AbstractActionController
{
    public function indexAction()
    {
        if (! $this->getServiceLocator()
                 ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('usuarios/login');
        }
         
        return new ViewModel(
                array(
                    'usuario'=> $this->getServiceLocator()->get('AuthService')->getIdentity(),
                    ));
    }
}