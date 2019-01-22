
<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Payfully\Integrator\UrlGenerator;

$urlgenerator = new UrlGenerator("payfully_test", 'Ot9NTFP2ylGLnipUhkXSlEPpddlEY9HrBDF6veE7dqhgkqZ40UaLDxRo2S/F9+R5tBFFsso9RENCMrh11Dbl7g==', true);
$urlgenerator->setUser([
    'email'=> 'test@duvan.com',
    'fullName'=> 'test@duvan.com',
    'phone'=> '6466666666'
]);
$datetime = new DateTime('2010-12-30 23:21:46');
$datetime = $datetime->format(DateTime::ATOM);
$urlgenerator->setApplication([
  'dueDate'=> $datetime,
  'shareOfCommission'=> 123,
  'dealInformation'=> [
    'propertyAddress'=> 'test',
    'remove'=> 'remove',
    'propertyType'=> 'test',
    'isNewConstruction'=> false,
    'isShortSale'=> true,
    'ratificationDate'=> $datetime,
    'closingDate'=> $datetime,
    'mlsId'=> 'test'
  ],
  'agentInformation'=> [
    'represents'=> '123',
    'completedTransactions'=> '345',
    'pendingContracts'=> 123,
    'activeListings'=> 3455,
    'fullName'=> 'adte',
    'email'=> 'test@test.com',
    'phoneNumber'=> '6466666666',
    'licenseNumber'=> 'test'
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
