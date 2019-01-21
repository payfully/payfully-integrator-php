
<?php 
require __DIR__ ."/../payfully-integrator.php";

$urlgenerator = new UrlGenerator("payfully_test",'Ot9NTFP2ylGLnipUhkXSlEPpddlEY9HrBDF6veE7dqhgkqZ40UaLDxRo2S/F9+R5tBFFsso9RENCMrh11Dbl7g==');
$urlgenerator->setUser([
    'email'=> 'usuario@asdsd.com',
    'fullName'=> 'usuario@asdsd.com',
    'phone'=> '6466666666'
]);
$datetime = new DateTime('2010-12-30 23:21:46');
$datetime = $datetime->format(DateTime::ATOM);
$urlgenerator->setApplication([
  'dueDate'=> $datetime,
  'shareOfCommission'=> 123,
  'dealInformation'=> [
    'propertyAddress'=> 'asdte',
    'remove'=> 'remove',
    'propertyType'=> 'asdte',
    'isNewConstruction'=> false,
    'isShortSale'=> true,
    'ratificationDate'=> $datetime,
    'closingDate'=> $datetime,
    'mlsId'=> 'asdte'
  ],
  'agentInformation'=> [
    'represents'=> '123',
    'completedTransactions'=> '345',
    'pendingContracts'=> 123,
    'activeListings'=> 3455,
    'fullName'=> 'adte',
    'email'=> 'dasdasd@dasd.sdd',
    'phoneNumber'=> '6466666666',
    'licenseNumber'=> 'asdte'
  ]
]);

$urlgenerator->setDocuments([
  [
    'type'=> 'contract',
    'url'=> 'https://google.com'
  ],
  [
    'type'=> 'idDocuments',
    'url'=> 'https://google.com'
  ]
]);

echo $urlgenerator->generate();