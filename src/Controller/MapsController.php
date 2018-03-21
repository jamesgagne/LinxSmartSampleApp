<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Validation\Validator;
use Cake\Mailer\Email;
/**
 * Locations Controller
 *
 * @property \App\Model\Table\LocationsTable $Locations
 *
 * @method \App\Model\Entity\Location[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MapsController extends AppController
{
    var $TPL  = array();
    public function initialize(){
        parent::initialize();
        $this->loadModel('Files');
        $this->loadModel('Locations');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->TPL['title'] = "LinxSmart Sample Assignment";
        $this->TPL['msg'] = "Please upload a CSV";
        $query = $this->Locations->find();
        //show upload page with proper flash message
        if ($query->isEmpty()){
            $this->redirect(
            ['controller' => 'Uploads', 'action' => 'redirected']
        );
        }
        else{
        $this->TPL['locations'] = $query;
        $this->viewBuilder()->layout('main');
        $this->set($this->TPL);
        }
        
        

    }

        /*
        Sends email with content provided by contact modal
        */
        public function email(){
            $data = $this->request->getData();
            $email = new Email('default');
            $email->from(['james.gagne@hotmail.ca' => 'LinxSmart Sample Application'])
                ->to('developer@linxsmart.com')
                ->subject('LinxSmart Sample Application Contact')
                ->send($data['msg']);
            echo json_encode("message sent!");
        }
   
}
