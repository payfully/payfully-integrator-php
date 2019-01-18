<?php 

require __DIR__ . '/vendor/autoload.php';
require "libs/aes-encription.php";
require "interface/integrator.php";

class UrlGenerator implements UrlGeneratorInterface
{
  private $relativeUrl; 
  private $aesKey; 
  public $user; 
  public $application = false; 
  public $documents = false; 

  // const PAYFULLY_URL = 'https://www.payfully.co';
  const PAYFULLY_URL = 'localhost:8000';
  /**
   * @param type $relativeUrl
   * @param type $aesKey
   */
  public function __construct($relativeUrl, $aesKey){
    if(!$relativeUrl){
      throw new Exception('RelativeUrl property must be provide.');
    }
    if(!$relativeUrl){
      throw new Exception('aesKey property must be provide.');
    }
    $this->relativeUrl = $relativeUrl;
    $this->aesKey = $aesKey;
  }
  public function generate()
  {
    // $this->validate();
    return $this->getDataEncoded();
    // return self::PAYFULLY_URL . "/integrations/".$this->relativeUrl."/".$this->getDataEncoded();
  }
  private function getDataEncoded() {
    $encodedData = [];
    $encodedData['user'] =  $this->user;
    // if($this->application) {
    //   $encodedData['application'] =  $this->application;
    // }
    // if($this->documents) {
    //   $encodedData['documents'] =  $this->documents;
    // }
    $encodedData = json_encode($encodedData, true);

    $aesEncript = new AES($encodedData, $this->aesKey);
    $dataEncoded = $aesEncript->encrypt($encodedData);
    return  $dataEncoded;
  }
  public function validate()
  {
    $this->validateUser();
    $this->validateApplication();
    $this->validateDocuments();
    throw new Exception('Errors.');
    return true;
  }
  public function validateUser()
  {
    if(!$this->user || !is_array($this->user)) {
      throw new Exception('User data must be an array');
    }

    foreach($this->user as $user){
      echo $user;
    }

  }
  public function validateApplication()
  {
    if(!$this->user || !is_array($this->user)) {
      throw new Exception('User data must be an array');
    }
  }
  public function validateDocuments()
  {
    if(!$this->user || !is_array($this->user)) {
      throw new Exception('User data must be an array');
    }
  }
}

// Example consume 
$urlgenerator = new UrlGenerator("payfully_test",'Ot9NTFP2ylGLnipUhkXSlEPpddlEY9HrBDF6veE7dqhgkqZ40UaLDxRo2S/F9+R5tBFFsso9RENCMrh11Dbl7g==');
$urlgenerator->user = [
    'email'=> 'usuario@asdsd',
    'fullName'=> 'usuario@asdsd.com',
    'phone'=> '123123',
];

$urlgenerator->application = [
  'dueDate'=> 'asdte',
  'shareOfCommission'=> 'asdasd',
  'dealInformation'=> [
    'propertyAddress'=> 'asdte',
    'propertyType'=> 'asdte',
    'isNewConstruction'=> true,
    'isShortSale'=> true,
    'ratificationDate'=> 'adte',
    'closingDate'=> 'adate',
    'mlsId'=> 'asdte'
  ],
  'agentInformation'=> [
    'represents'=> 'asdte',
    'completedTransactions'=> 'asdte',
    'pendingContracts'=> true,
    'activeListings'=> true,
    'fullName'=> 'adte',
    'email'=> 'adate',
    'phoneNumber'=> 'asdte',
    'licenseNumber'=> 'asdte'
  ]
];

$urlgenerator->documents = [
  [
    'type'=> 'contract',
    'url'=> 'https://google.com'
  ],
  [
    'type'=> 'contract_asdd',
    'url'=> 'https://google.com'
  ]
];

echo $urlgenerator->generate();