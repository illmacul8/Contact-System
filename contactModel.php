<?php
class contactModel {
    public $contact_list = [];


    public function getInsertId(){
        return count($this->contact_list) + 1;
    }
    
    public function createContact($name, $email, $house_address) {   
        $this->contact_list[] = [
            "id" => $this->getInsertId(),
            "name" => $name,
            "email" => $email,
            "address" => $house_address
        ];
        return true;
    }
    
    public function deleteContact($contact) {
        unset($this->contact_list[$contact - 1]);
        return true;
    }
   
    public function listContact(array $contacts = []){
        $list = count($contacts)  === 0 ? $this->contact_list : $contacts;
        return $list;
    }

    public function searchContact($searchTerm){

        $results = [];
        foreach($this->contact_list as $item) {
            $searchString = $item['name'].' '.$item['email'].' '.$item['address'];
            if(preg_match("/{$searchTerm}/",$searchString)){
                $results[] = $item;
            }
            
            // if(  
            //     preg_match("/{$searchTerm}/i", $item['name']) ||
            //     preg_match("/{$searchTerm}/i", $item['email']) ||
            //     preg_match("/{$searchTerm}/i", $item['address'])
            // ){
            //     $results[] = $item;
            // }
        }

        return $results;
    }

    public function checkEmailExists($email){
        $records = [];
        foreach($this->contact_list as $item) {
            if(  strtolower($item['email']) === strtolower($email) ){
                $records[] = $item;
            }
        }
        return $records;
    }
    
};


?>

