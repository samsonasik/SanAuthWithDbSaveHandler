<?php
//filename : module/SanAuthWithDbSaveHandler/src/SanAuthWithDbSaveHandler/Controller/AuthController.php
namespace SanAuthWithDbSaveHandler\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use SanAuthWithDbSaveHandler\Storage\IdentityManagerInterface;

class AuthController extends AbstractActionController
{
    protected $identityManager;

    //we will inject authService via factory
    public function __construct(IdentityManagerInterface $identityManager)
    {
        $this->identityManager = $identityManager;
    }

    public function indexAction()
    {
        if ($this->identityManager->hasIdentity()) {
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
                $result = $this->identityManager->login($dataform['username'], $dataform['password']);

                if ($result->isValid()) {
                    //authentication success
                    $identityRow = $this->identityManager->getAuthService()->getAdapter()->getResultRowObject();
                    $this->identityManager->storeIdentity(
                         array('id'          => $identityRow->id,
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
        $this->identityManager->logout();

        return $this->redirect()->toRoute('auth');
    }
}
