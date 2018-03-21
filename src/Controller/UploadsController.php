<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Validation\Validator;
/**
 * Locations Controller
 *
 * @property \App\Model\Table\LocationsTable $Locations
 *
 * @method \App\Model\Entity\Location[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UploadsController extends AppController
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
        
        if ($this->request->is('post')){

            $this->processPostData();
        
        }
        $this->viewBuilder()->layout('main');
        $this->set($this->TPL);
        
        
    }
    /*
    validates and saves the uploaded file, parses the file
    */
    public function processPostData(){
        if(!empty($this->request->data['CSV']['name'])){
            //
            $fileName = $this->request->data['CSV']['name'];
            $filePath = "files/".$fileName;
            $url = Router::url('/', true) . $filePath;
            
            $fileData = array('name' => $fileName, 
                              'path' => $url, 
                              'created' => date("Y-m-d H:i:s"), 
                              'CSV' => $this->request->getData());
            //
            $file = $this->Files->newEntity();
            $file = $this->Files->patchEntity($file,$fileData);
            
            if ($file->errors()){//validation failed
                $this->Flash->error(__($file->errors()['CSV']['type']));
            }
            else{//no errors validating file entity
            
                if (move_uploaded_file($this->request->data['CSV']['tmp_name'], $filePath)) {
                    
                    
                    if ($this->Files->save($file)){
                        
                        $this->Flash->success(__("File uploaded successfully"));
                        
                        $csv = $this->getCSVData($filePath);
                        
                        foreach ($csv as $key => $value) {
                            $this->addLocation($value, $file);  
                        }
                    }
                    else{//file record insert failed
                        $this->Flash->error(__("File insert record failed"));
                    }
                }
                else{//file save failed
                    $this->Flash->error(__("File move failed"));
                }
            }
        }
        else{//no file provided
            $this->Flash->error(__("Please provide a valid file"));
        }
    }
    /*
    validates and adds locations as necessesary
    */
    public function addLocation($value, $file){
        $loc = $this->Locations->newEntity();
        $value['file_id'] = $file->id;
        $loc = $this->Locations->patchEntity($loc, $value);
        if(!$this->Locations->save($loc)){
            $error = $loc->errors();
            if (!$error['street']['custom']=='dup'){
                
                $this->Flash->error(__("Location {0} {1} {2} {3} insert record failed",$value['street'],$value['city'],$value['zip'],$value['country']));
        
            }
            
        }
    }

    /*
    creates associative array from the csv provided
    */
    public function getCSVData($filePath){
        //Read the file into an array
        $csv = array_map('str_getcsv', file($filePath));
        //change header to be key of each value supplied
        array_walk($csv, function(&$a) use ($csv) {
        $a = array_combine($csv[0], $a);
        });
        array_shift($csv); //remove header row
        return $csv;
    }

    /*
    shows appropriate flash message when redirected here
    */
    public function redirected(){
        $this->Flash->error(__("Please upload some locations"));
        $this->index();
        $this->render('index');
    }


   
}
