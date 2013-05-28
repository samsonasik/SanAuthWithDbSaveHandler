<?php
//filename : module/SanAuthWithDbSaveHandler/src/SanAuthWithDbSaveHandler/Controller/AuthController.php
namespace SanAuthWithDbSaveHandler\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    protected $authService;
    
    //we will inject authService via factory
    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }
    
    public function indexAction()
    {
        if ($this->authService->getStorage()->getSessionManager()->getSaveHandler()
                 ->read($this->authService->getStorage()->getSessionId())) {
            //redirect to success controller...
            return $this->redirect()->toRoute('success');
        }
        
        $form = $this->getServiceLocator()->get('FormElementManager')
                                          ->get('SanAuthWithDbSaveHandler\Form\LoginForm');   
        $viewModel = new ViewModel();
        
        //initialize error...
        $viewModel->setVariable('error', '');
        //authentication block...
        $this->authenticate($form, $viewModel);
        
        $viewModel->setVariable('form', $form);
        return $viewModel;
    }
    
    /** this function called by indexAction to reduce complexity of function */
    protected function authenticate($form, $viewModel)
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $dataform = $form->getData();
                
                $this->authService->getAdapter()
                                       ->setIdentity($dataform['username'])
                                       ->setCredential($dataform['password']);
                $result = $this->authService->authenticate();
                if ($result->isValid()) {
                    //authentication success
                    $resultRow = $this->authService->getAdapter()->getResultRowObject();
                   
                    $this->authService->getStorage()->write(
                         array('id'          => $resultRow->id,
                                'username'   => $dataform['username'],
                                'ip_address' => $this->getRequest()->getServer('REMOTE_ADDR'),
                                'user_agent'    => $request->getServer('HTTP_USER_AGENT'))
                    );
                    
                    return $this->redirect()->toRoute('success', array('action' => 'index'));;       
                } else {
                    $viewModel->setVariable('error', 'Login Error');
                }
            }
        }
    }
    
    public function logoutAction()
    {
        $this->authService->getStorage()->clear();
        return $this->redirect()->toRoute('auth');
    }
}
