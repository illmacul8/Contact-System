<?php
require "contactModel.php";

class contactController {
    
    public $contactList;
    
    public function __construct(){
        $this->contactList = new contactModel();
    }
    public function init() {
        $this->welcomeText();
        $this->processOptions();
    }
    
    public function welcomeText() {
            $welcomeText = "";
            $welcomeText .= "Hello, WELCOME TO MY GOOGLE CONTACT SYSTEM APP\n\n";
            $welcomeText .= "Press 1: To create a Contact\n";
            $welcomeText .= "Press 2: To delete a contact\n";
            $welcomeText .= "Press 3: To view all contacts on the Contact List\n";
            $welcomeText .= "Press 4: To Search a contact\n";
            $welcomeText .= "Press 5: To Quit\n";
            echo $welcomeText;
    }
    
    public function processOptions(){
        $userInput = readline("Enter a number to proceed: ");
        $input = trim($userInput);
        $allowed_values = [1,2,3,4,5];
        if(!in_array($input, $allowed_values)){
            echo "Invalid parameter supplied\n";
            $this->processOptions();
        }
        switch ($input) {
            case 1:
                $this->createContactAction();
                break;
            case 2:
                $this->deleteContactAction();
                break;
            case 3:
                $this->listContactsAction();
                break;
            case 4:
                $this->searchContactAction();
                break;
            case 5:
                exit;
                break;
            default:
                echo "Try Again, Wrong Input\n";
        }
        $this->processOptions();
    }


    public function createContactAction(){
        $namePrompt = "Your Name: ";
        $name = readline($namePrompt);
        if(!$this->validateInput($name)){
            $name = readline($namePrompt);
        }
        $userEmailPrompt = "Your Email: ";
        $email = readline($userEmailPrompt);
        if(!$this->validateInput($email) || $this->emailExists($email)){
            $email = readline($userEmailPrompt);
        }
        $userAddressPrompt = "Your Address: ";
        $address = readline($userAddressPrompt);
        if(!$this->validateInput($address)){
            $address = readline($userAddressPrompt);
        }
        if($this->contactList->createContact($name,$email, $address)){
            echo "Contact Created Successfully \n";
        };
    }

    
    public function deleteContactAction() {
        $deleteContactIdPrompt = "Please enter the Contact ID to Delete: ";
        $deleteContactId = readline($deleteContactIdPrompt);
        if(!$this->validateInput($deleteContactId)){
            $deleteContactId = readline($deleteContactIdPrompt);
        }
        if($this->contactList->deleteContact($deleteContactId)){
            echo "Contact Delete successful\n";
        }
    }

    public function listContactsAction(){
        $contactList = $this->contactList->listContact();
        $this->renderContacts($contactList);
    }

    private function renderContacts(array $contactList = []){

        if(count($contactList) > 0){
            $mask = "|%5s |%-30s |%-30s  |%-30s |\n";
            printf($mask, 'id', 'name', 'email', 'address');
            foreach($contactList as $list){
                printf($mask, $list['id'], $list['name'], $list['email'], $list['address']);
            }
        }else{
            echo "no results available";
        }
    }

    private function searchContactAction(){

        $searchPrompt = "Search contacts: ";
        $searchTerm = readline($searchPrompt);
        if(!$this->validateInput($searchTerm)){
            $searchTerm = readline($searchPrompt);
        }
        $contactList = $this->contactList->searchContact($searchTerm);
        $this->renderContacts($contactList);

    }

    private function validateInput($input){
        if(!trim($input)){
            echo "Invalid input \n";
            return false;
        }
        return true;
    }

    
    private function emailExists($email){
        $records = $this->contactList->checkEmailExists($email);
        if(count($records) > 0){
            echo "Email already exists \n";
            return true;
        }
        return false;
    }
}
$initProcess = new contactController();
$initProcess->init();