<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Location Entity
 *
 * @property int $id
 * @property string $street
 * @property string $city
 * @property string $state
 * @property int $zip
 * @property string $country
 */
class Location extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'street' => true,
        'city' => true,
        'state' => true,
        'zip' => true,
        'country' => true,
        'file_id' =>true,
        'lng' =>true,
        'lat'=>true
    ];

    

}
