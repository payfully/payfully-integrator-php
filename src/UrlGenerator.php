<?php

namespace Payfully\Integrator;

use Payfully\Integrator\AES;
use Payfully\Integrator\Env;
use libphonenumber\PhoneNumberUtil;
use Exception;

class UrlGenerator
{
    private $relativeUrl;
    private $aesKey;
    private $env;
    public $user;
    public $application = false;
    public $documents = false;

    const PAYFULLY_URL_PROD = 'https://integration.payfully.co';
    const PAYFULLY_URL_STAGE = 'https://integration-stage.payfully.co';
    const PHONE_NUMBER_REGION = 'US';
    /**
     * @param type $relativeUrl
     * @param type $aesKey
     */
    public function __construct($relativeUrl, $aesKey, $env = Env::Production)
    {
        if (!$relativeUrl) {
            throw new Exception('RelativeUrl property must be provide.');
        }
        if (!$relativeUrl) {
            throw new Exception('aesKey property must be provide.');
        }
        $this->relativeUrl = $relativeUrl;
        $this->aesKey = $aesKey;
        $this->env = $env;
    }

    /**
     * @param user $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @param user $application
     */
    public function setApplication($application)
    {
        $this->application = $application;
    }

    /**
     * @param documents $documents
     */
    public function setDocuments($documents)
    {
        $this->documents = $documents;
    }

    public function generate()
    {
        $this->validate();
        return ($this->env === Env::Production ? self::PAYFULLY_URL_PROD : self::PAYFULLY_URL_STAGE). "/integrations/".$this->relativeUrl."/".$this->getDataEncoded();
    }

    private function getDataEncoded()
    {
        $encodedData = [];
        $encodedData['user'] =  $this->user;
        if ($this->application) {
            $encodedData['application'] =  $this->application;
        }
        if ($this->documents) {
            $encodedData['documents'] =  $this->documents;
        }
        $encodedData = json_encode($encodedData, true);

        $aesEncript = new AES($encodedData, $this->aesKey);
        $dataEncoded = $aesEncript->encrypt();
        return  $dataEncoded;
    }
    public function validate()
    {
        $this->validateUser();
        $this->validateApplication();
        $this->validateDocuments();

        return true;
    }
    public function validateUser()
    {
        $fields = [
      'email'=> [
        'required' => true,
        'type' => 'email'
      ],
      'fullName'=> [
        'required' => true,
        'type' => 'string'
      ],
      'phone' => [
        'required' => true,
        'type' => 'phone'
      ]
    ];
    
        if (!$this->user || !is_array($this->user)) {
            throw new Exception('User data must be an array');
        }

        foreach ($this->user as $key => $property) {
            if (key_exists($key, $fields)) {
                $this->validateType($fields[$key]['type'], $property, $key);
            } else {
                unset($this->user[$key]);
            }
        }

        foreach ($fields as $key => $field) {
            if (!key_exists($key, $this->user)) {
                throw new Exception("Property '$key' is a required field for User data.");
            }
        }
    }
    public function validateApplication()
    {
        $fields = [
      'dueDate'=> [
        'required' => false,
        'type' => 'date'
      ],
      'shareOfCommission'=> [
        'required' => false,
        'type' => 'numeric'
      ],
      'dealInformation'=> [
        'required' => false,
        'type' => 'array',
        'data' => [
            'propertyAddress'=> [
            'required' => false,
            'type' => 'string'
          ],
          'propertyType'=> [
            'required' => false,
            'type' => 'string',
          ],
          'isNewConstruction'=> [
            'required' => false,
            'type' => 'boolean'
          ],
          'isShortSale'=> [
            'required' => false,
            'type' => 'boolean'
          ],
          'ratificationDate'=> [
            'required' => false,
            'type' => 'date'
          ],
          'closingDate'=> [
            'required' => false,
            'type' => 'date'
          ],
          'mlsId'=> [
            'required' => false,
            'type' => 'string'
          ]
        ]
      ],
      'agentInformation'=> [
        'required' => false,
        'type' => 'array',
        'data' => [
        'represents'=> [
          'required' => false,
          'type' => 'string'
        ],
        'completedTransactions'=> [
          'required' => false,
          'type' => 'numeric'
        ],
        'pendingContracts'=> [
          'required' => false,
          'type' => 'numeric'
        ],
        'activeListings'=> [
          'required' => false,
          'type' => 'numeric'
        ],
        'fullName'=> [
          'required' => false,
          'type' => 'string'
        ],
        'email'=> [
          'required' => false,
          'type' => 'email'
        ],
        'phoneNumber'=> [
          'required' => false,
          'type' => 'phone'
        ],
        'licenseNumber'=> [
          'required' => false,
          'type' => 'string'
        ]
        ]
      ]
    ];
        if ($this->application) {
            if (!is_array($this->application)) {
                throw new Exception('Application data must be an array');
            }
            foreach ($this->application as $key => $property) {
                if (key_exists($key, $fields)) {
                    if ($fields[$key]['type'] === 'array') {
                        if ($property && !is_array($property)) {
                            throw new Exception("'$key' must be an array");
                        }
                        foreach ($property as $keyInter => $propertyInter) {
                            if (key_exists($keyInter, $fields[$key]['data'])) {
                                $this->validateType($fields[$key]['data'][$keyInter]['type'], $propertyInter, $keyInter);
                            } else {
                                unset($this->application[$key][$keyInter]);
                            }
                        }
                    } else {
                        $this->validateType($fields[$key]['type'], $property, $key);
                    }
                } else {
                    unset($this->application[$key]);
                }
            }
        }
    }
    public function validateDocuments()
    {
        $fields = [
      'type'=> [
        'required' => true,
        'type' => 'document'
      ],
      'url'=> [
        'required' => true,
        'type' => 'string'
      ]
    ];
        if ($this->documents) {
            if (!is_array($this->documents)) {
                throw new Exception('Documents data must be an array');
            }
            if (!$this->application) {
                $this->setDocuments(false);
            } else {
                foreach ($this->documents as $key => $document) {
                    $this->validateType($fields['type']['type'], $document['type'], $key);
                    $this->validateType($fields['url']['type'], $document['url'], $key);
                }
            }
        }
    }
    public function validateType($type, $value, $field)
    {
        switch ($type) {
      case 'email':
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            throw new Exception("'Value '$value' for '$field' is not a valid email address");
        }
      break;
      case 'date':
        if ($value) {
            if (preg_match('/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/', $value) > 0) {
                return true;
            } else {
                throw new Exception("Value '$value' for '$field' is not a valid date ISO 8601 formatted.");
            }
        }
      break;
      case 'boolean':
        if (!is_bool($value)) {
            throw new   Exception("Value '$value' for '$field' is not a valid boolean.");
        }
      break;
      case 'phone':
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $swissNumberProto = $phoneUtil->parse($value, self::PHONE_NUMBER_REGION);
        } catch (NumberParseException $e) {
            throw new Exception("Error parsing the phone number");
        }
        $isValid = $phoneUtil->isValidNumber($swissNumberProto);
        if (!$isValid) {
            throw new Exception("Value '$value' for '$field' is not a valid phone number");
        }
      break;
      case 'numeric':
        if (!is_numeric($value)) {
            throw new Exception("Value '$value' for '$field' is not numeric");
        }
      break;
      case 'document':

      $docTypes = [
        "idDocuments",
        "contract",
        "commission_report",
        "agreement",
        "money_deposit",
        "pre_approval_letter",
        "inspection_report",
        "bank_approval"
      ];
        if (!in_array($value, $docTypes)) {
            throw new Exception("Document type value '$value' for 'document[$field]' is not valid");
        }
      break;
      default:
        return true;
      break;

    }
    }
}
