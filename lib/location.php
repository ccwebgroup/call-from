<?php
/**
 * Library for abstraction of methods to use Whitepages Pro API
 * @require     PHP5
 *
 * @author      Kushal Shah
 * @date        2014-06-02
 */

class Location
{
    public $entity;
    public $city;
    public $postal_code;
    public $state_code;
    public $country_code;
    public $is_receiving_mail;
    public $usage;
    public $delivery_point;
	public $fulladdress ;
	public $latitude ;
	public $longitude ;
    public $is_deliverable;
    public $standard_address_line1;
    public $standard_address_line2;
	public $standard_address_location;
    public $zip4;


    public function __construct($entity)
    {
        $this->entity = $entity;
        $this->city = $this->city();
        $this->postal_code = $this->postal_code();
        $this->state_code = $this->state_code();
        $this->country_code = $this->country_code();
        $this->is_receiving_mail = $this->is_receiving_mail();
        $this->usage = $this->usage();
        $this->delivery_point = $this->delivery_point();
		$this->fulladdress = $this->fulladdress();
		$this->latitude = $this->latitude();
		$this->longitude = $this->longitude();
        $this->is_deliverable = $this->is_deliverable();
        $this->standard_address_line1 = $this->standard_address_line1();
        $this->standard_address_line2 = $this->standard_address_line2();
		$this->standard_address_location = $this->standard_address_location();
        $this->zip4 = $this->zip4();
    }

    public function data()
    {
        return array(
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'state_code' => $this->state_code,
            'country_code' => $this->country_code,
            'is_receiving_mail' => $this->is_receiving_mail,
            'usage' => $this->usage,
            'delivery_point' => $this->delivery_point,
			'fulladdress' => $this->fulladdress,
			'latitude' => $this->latitude,
			'longitude' => $this->longitude,
            'is_deliverable' => $this->is_deliverable,
            'standard_address_line1' => $this->standard_address_line1,
            'standard_address_line2' => $this->standard_address_line2,
			'standard_address_location' => $this->standard_address_location,
            'zip4' => $this->zip4
        );
    }


    private function city()
    {
        return  $this->entity['city'];
    }

    private function postal_code()
    {
        return  $this->entity['postal_code'];
    }

    private function state_code()
    {
        return  $this->entity['state_code'];
    }

    private function country_code()
    {
        return  $this->entity['country_code'];
    }

    private function is_receiving_mail()
    {
        return  $this->entity['is_receiving_mail'];
    }

    private function usage()
    {
        return  $this->entity['usage'];
    }

    private function delivery_point()
    {
        return  $this->entity['delivery_point'];
    }
	private function fulladdress()
    {
        return  $this->entity['address'];
    }
	private function latitude()
    {
        return  $this->entity['lat_long']['latitude'];
    }
	private function longitude()
    {
        return  $this->entity['lat_long']['longitude'];
    }

    private function is_deliverable()
    {
        return  $this->entity['is_deliverable'];
    }

    private function standard_address_line1()
    {
        return  $this->entity['standard_address_line1'];
    }

    private function standard_address_line2()
    {
        return  $this->entity['standard_address_line2'];
    }
    private function standard_address_location()
    {
        return  $this->entity['standard_address_location'];
    }
    private function zip4()
    {
        return  $this->entity['zip4'];
    }
}

