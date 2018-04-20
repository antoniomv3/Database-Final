<?php
require('finalModel.php');
require('finalView.php');

class finalController {
   private $model;
   private $view;
   private $nav = '';
   private $action = '';
   //This is the url to the site, which will be passed where needed to redirect pages back to index.php.
   private $url = 'https://web.dsa.missouri.edu/~amv7vc/';
    
   public function __construct() {
   //On starting a new instance of the controller, it first starts instances of the model and view, then it grabs 'nav' data from the url, and if nothing is set it sets it to home. This nav data will be passed later to determine what content is displayed on the page. It also grabs 'action' from post data if it exists. 
      $this->model = new finalModel();
      $this->view = new finalView();
        
      $this->nav = $_GET['nav'] ? $_GET['nav'] : 'home';
      $this->action = $_POST['action'];
    }
   public function __destruct() {
      $this->model = null;
      $this->view = null;
   }
    
   public function run(){
   //This is what is called when the index page is loaded. It will perform actions based on the action value obtained from post data.
      switch($this->action) {
         case 'login':
            $this->handleLogin();
            break;    
         case 'search':
            $this->nav = "search";
            break;
         case 'selectRecord':
            $this->nav = 'selectRecord';
            break;
         case 'deleteStudent':
            $this->nav = 'deleteStudent';
            break;
         
         default:   
            break;
      }
      $this->runPage();
   }
   
   private function runPage(){
   //The page is run with these two functions, the first sends the nav data obtained earlier to the model where it will receive back an array of variables. The second sends those variables to the view where it will be returned as a complete html string to print out. 
      list($source, $formOptions, $logStatus, $tableData, $recordData, $group, $studentSchools, $tableSource, $studentData) = $this->model->preparePageContent($this->nav);
      print $this->view->pageView($source, $formOptions, $logStatus, $tableData, $this->url, $recordData, $group, $studentSchools, $tableSource, $studentData);
   }
   
   private function handleLogin(){
   //This function is called when a login is attempted. It calls a login function in the model. If there is a match, it redirects the page to home, if not it returns the user to the login page to try again. 
      if($this->model->processLogin() === 'true'){
         header('Location: ' .$this->url. '?nav=home');
      }
      else{
         header('Location: ' .$this->url. '?nav=login');
      }
   }
}
?>

