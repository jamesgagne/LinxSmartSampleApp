<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Xml;
use Cake\ORM\TableRegistry;


/**
 * Locations Model
 *
 * @method \App\Model\Entity\Location get($primaryKey, $options = [])
 * @method \App\Model\Entity\Location newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Location[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Location|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Location patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Location[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Location findOrCreate($search, callable $callback = null, $options = [])
 */
class LocationsTable extends Table
{
    var $customMsg = "";
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('locations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('street')
            ->maxLength('street', 250)
            ->requirePresence('street', 'create')
            ->notEmpty('street')
            ->add('street', 'custom', [
            'rule' => function ($value, $context){ 
            //custom function to validate if record already exists and update it
                $flag = true;
                $locationsTable = TableRegistry::get('Locations');
                $query = $locationsTable->find();
                $query->where(['street' => $context['data']['street'], 'city'=>$context['data']['city'], 'state'=>$context['data']['state']]);
                foreach ($query as $existing) {
                    $flag = false;
                    $loc = $locationsTable->get($existing['id']);
                    $loc->set($context['data']);
                    $locationsTable->save($loc);
                }
                return $flag; 
        },
            'message' => 'dup'
        ]);

        $validator
            ->scalar('city')
            ->maxLength('city', 250)
            ->requirePresence('city', 'create')
            ->notEmpty('city');

        $validator
            ->scalar('state')
            ->maxLength('state', 250)
            ->requirePresence('state', 'create')
            ->notEmpty('state');

        $validator
            ->integer('zip')
            ->requirePresence('zip', 'create')
            ->notEmpty('zip');

        $validator
            ->scalar('country')
            ->maxLength('country', 250)
            ->requirePresence('country', 'create')
            ->notEmpty('country');
        $validator
            ->integer('file_id')
            ->requirePresence('file_id', 'create')
            ->notEmpty('file_id');
        $validator
            ->scalar('lng')
            ->maxLength('lng', 250);
        $validator
            ->scalar('lat')
            ->maxLength('lat', 250);
        return $validator;
    }

    /*
    after new location has been saved get and update its geolocation coordinates from google maps api
    */    
    public function afterSave($event,$entity){
    if ($entity->isNew()) {
        $locationsTable = TableRegistry::get('Locations');
        $address = urlencode(trim($entity['street']). " " . trim($entity['city']).", ".trim($entity['state'])." ". trim($entity['country']));
        //key is hardcoded here but should normally be held in a settings table
        $key = 'AIzaSyAK-NWUAnfQXs2j5vRGc-QfH7TgUDyMVGA';
    // Call the geocoding API with this location
        $xmlFile = file_get_contents("https://maps.googleapis.com/maps/api/geocode/xml?address={$address}&sensor=false&key={$key}");
    // get the longitude and latitude data from this location
        $xmlObj = Xml::build($xmlFile);
        //die();
        if ($xmlObj->status == 'ZERO_RESULTS'){//no result found so delete the record
            $locationsTable->delete($locationsTable->get($entity['id']));
        }
        else{
            $longitude = (string)$xmlObj->result->geometry->location->lng;
            $latitude = (string)$xmlObj->result->geometry->location->lat ; 

            $location = $locationsTable->get($entity['id']);

            $location->lng = $longitude;
            $location->lat = $latitude;
            $locationsTable->save($location);
        }
        
    }

    }
}