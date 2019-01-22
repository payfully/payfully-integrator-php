# Payfully Integrator PHP

- [Payfully Integration Guide](#payfully-integration-guide)
  * [Introduction](#introduction)
    + [Supported Features Overview](#supported-features-overview)
  * [Integration Links](#integration-links)
    + [Data Structure](#-data-structure)
    + [Credentials Explained](#credentials-explained)
    + [Generating Integration URLs](#generating-integration-urls)
      - [Generate Integration URL with our PHP library](#api-link-generation-php)
      - [Generate Integration URL with our POST API](#generate-integration-url-with-our-post-api)
      - [Generate Integration URL Manually](#generate-integration-url-manually)
  * [Creating User Accounts](#creating-user-accounts)
    + [Required Data](#-required-data)
  * [Creating Advance Applications](#creating-advance-applications)
    + [Required Data](#-required-data)
    + [Attaching Documents to Advance Application](#-attaching-documents-to-advance-application)

## Introduction

Welcome! We greatly appreciate your interest in [Payfully](https://www.payfully.co/) platform!

In this guide we are going to walk you through our integration features.

If you have any questions please don't hesitate to [contact us](alberto@payfully.co).

### Supported Features Overview

Currently we offer 2 main features:
- Quick User Registration & Log In
- Submitting Advance Applications Automatically

Both of the features work the following way:
1. You need to obtain the credentials: relativeUrl and aesKey.
2. Gather the required data (in a JSON format)
3. Generate a special URL (referred as IntegrationURL) and place it at your website.
4. User which is logged into your website clicks the link and gets redirected to Payfully.
5. Once the user follows the link he/she is redirected to our website.
Then we automatically log the agent into Payfully (and create Payfully account if needed).
We can also generate the Advance Application at the same time.

**Important:**
Integration URLs should be considered as sensitive information and
should not be exposed to anyone except the users (which they are generated for).
As following the Integration URL allows logging into Payfully account.

## Integration Links

The Integration URL looks as follows:
`Production`
`https://www.payfully.co/integrations/[relativeUrl]/[encodedData]`
`Stage`
`https://stage.payfully.co/integrations/[relativeUrl]/[encodedData]`
- *relativeUrl* - is going to be provided by Payfully Admin.

- *encodedData* - is a **JSON data** that is first **AES Encrypted** and then **base64 encoded**.
This data should contain agent information + optionally data for the Advance Application.

The exact [data structure](#data-structure) and [encryption/encoding](#api-link-generation) are covered in the following sections.

The example of an Integration URL:
`https://wwww.payfully.co/integrations/SuperAgency/VTJGc2RHVmtYMTlDSlo0Uk16TjJKOFZNZU4rcmc3VWNobUVXMjNtQzQ4ST0=`
`https://stage.payfully.co/integrations/SuperAgency/VTJGc2RHVmtYMTlDSlo0Uk16TjJKOFZNZU4rcmc3VWNobUVXMjNtQzQ4ST0=`

### <a name="data-structure"></a> Data Structure

JSON data encoded in IntegrationURL should have the following structure:

```
{
  "user": { ... } (required) Agent information. Used to log in and create account if needed.
  "application": { ... } (optional) Advance Application info. Used to create an Advance Application if present.
  "documents": { ... } (optional) Used to upload the documents for Advance Application if present.
}
```

Please refer to the specific sections to get more detail: 
* [user](#user-required-data)
* [application](#aa-required-data)
* [documents](#document-upload)


### Credentials Explained

In order to generate Integration URLs you will need to get the following credentials:

- *relativeUrl* - used as a part of IntegrationURL. Can be changed by Payfully Admin by your request.
- *aesKey* - AES Key used to encrypt the data.
Please immediately contact Payfully Admin in case it gets exposed/compromised.
Admin will rotate it and provide you with the new key.

Please contact Payfully Administrator to get them.

### Generating Integration URLs

There are 3 ways to generate Integration URLs.
In all cases you will need to have `relativeUrl` and `aesKey`.

#### <a name="api-link-generation-php"></a> Generate Integration URL with our PHP library

To generate the Integration URL using the PHP library:
1. Add the library to composer.
2. Form PHP array containing the required [data](#data-structure)
2. Instance the library object, and set the relativeUrl, aesKey and test values inside the constructor. 
2. Set the Data to each section.
4. Call the method `generate()`

Here is an example:

```php

require __DIR__ . '/vendor/autoload.php';

$relativeUrl = 'SuperAgency';
$aesKey = "Qkoghsks1Oe3V+/s+wtV6b1FFmM+YdQCg0mGPTiO3xofssrcsgR6yA3rvsSIyq/85DiHm/7BIbrEg1GOL1soag==";
$test = true;
$urlgenerator = new UrlGenerator($relativeUrl, $aesKey, $test);
$urlgenerator->setUser([...]);
$urlgenerator->setApplication([...]);
$urlgenerator->setDocuments([...]);

echo $urlgenerator->generate();
```

The code above outputs:
```text
Production 

https://wwww.payfully.co/integrations/SuperAgency/VTJGc2RHVmtYMS9EY0FEdnJ0MFdhSkJqdGlIYXB1ZVF5cWE2VkpYSXhiTT0=

Stage

https://stage.payfully.co/integrations/SuperAgency/VTJGc2RHVmtYMS9EY0FEdnJ0MFdhSkJqdGlIYXB1ZVF5cWE2VkpYSXhiTT0=

```

Tested with PHP v7.1

####<a name="generate-integration-url-with-our-post-api"></a> Generate Integration URL with our POST API

We provide a POST API for easy Integration URL generation.

Once you have `relativeUrl` and `aesKey` please use:

```bash
curl -X POST \
  https://st9rwv8ued.execute-api.us-west-2.amazonaws.com/dev/url \
  -H 'Cache-Control: no-cache' \
  -d '{
	"relativeUrl": "YOUR_RELATIVE_URL",
	"aesKey": "YOUR_AES_KEY",
	"data": { PUT_THE_DATA_HERE }
}'
```

The `data` should be a JSON document of the [structure](#data-structure) described above.

The example response body:
```json
{"url":"https://www.payfully.co/integrations/SuperAgency/VTJGc2RHVmtYMStyeERjb1FvMmEwdFhPblhJT2xQMnRyelVkUXFUTXZtVT0="}
```

#### <a name="api-link-generation-manually"></a>Generate Integration URL Manually

To generate the Integration URL manually you need to:
1. Form a PHP array containing the required [data](#data-structure)
2. **AES Encrypt** the string using your aesKey
3. **base64** encode the result
4. Concat base URL (https://www.payfully.co/integrations/), relativeUrl and the encodedData the following way:
`https://www.payfully.co/integrations/[relativeUrl]/[encodedData]`

Here is an example written in PHP:

```php

function generateIntegrationURL($data, $relativeUrl, $aesKey)
{
  $iv = mb_substr($aesKey, 0, 16);
  $salt = openssl_random_pseudo_bytes(256);
  $iterations = 999;
  $hashKey = hash_pbkdf2('sha512', $aesKey, $salt, $iterations, 64);
  $dataJson = json_encode($data, true);
  $encryptedData = openssl_encrypt($dataJson, 'AES-256-CBC', hex2bin($hashKey), OPENSSL_RAW_DATA, $iv);
  $encryptedData = base64_encode($encryptedData);
  unset($hashKey);
  $output = ['ciphertext' => $encryptedData, 'salt' => bin2hex($salt)];
  unset($encryptedString, $iv, $salt);
  $dataEncoded = base64_encode(json_encode($output));

  return  "https://www.payfully.co/integrations/".$relativeUrl."/".$dataEncoded;
}
$relativeUrl = 'SuperAgency';
$aesKey = "Qkoghsks1Oe3V+/s+wtV6b1FFmM+YdQCg0mGPTiO3xofssrcsgR6yA3rvsSIyq/85DiHm/7BIbrEg1GOL1soag==";
$data = [
  'user' => [
    'email'=> 'user@email.com',
    'fullName'=> 'username',
    'phone'=> '6466666666'
  ]
  // 'application' => [...],
  // 'documents' => [...]
  ];
$url = generateIntegrationURL($data, $relativeUrl, $aesKey);

echo $url;
```

The code above outputs:
```text
https://www.payfully.co/integrations/SuperAgency/VTJGc2RHVmtYMS9EY0FEdnJ0MFdhSkJqdGlIYXB1ZVF5cWE2VkpYSXhiTT0=
```

Tested with PHP v7.1

## Creating User Accounts
### <a id="user-required-data" /> Required Data

Each Integration URL must contain the user information.

```
[
  "user" => [
     "email"=> "...", (required) [string] Agent email
     "fullName" => "...", (required) [string] Agent full name
     "phone" => "..." (required) [string] Agent phone number; Must be a valid US phone number; Format: '+19179246228'
  ]
]
```

We are going to create a Payfully account basing on this data.

Note:
* The password is generated and send over the email along with the reset password link.
* If the user with such email already exists **and** created over the integration we just log the user in.
(e.g. that could happen if agent follow the same Integration URL twice or more times)


## Creating Advance Applications

If the Integration URL data include `application` property, we create the Advance Application.

If you have internal IDs for deals please provide it as `dealInformation['mlsId']`.
It is going to be used to avoid duplicating Advance Applications.

If mlsId Is missing it's going to be auto-generated by combining `dealInformation['closingDate']` and `dealInformation['propertyAddress']`.

Any optional data that was not provided by Integration URL can be filled later manually.

### <a name="aa-required-data"></a> Required Data
The following data structure is expected:

```
[
  'dueDate' => "...", (optional) [string] ISO 8601 Date String
  'shareOfCommission' => ..., (optional) [number]
  'dealInformation' => [
    'propertyAddress' => "...", (optional) [string] address string
    'propertyType' => "...", (optional) [string] 
    'isNewConstruction' => true/false, (optional) [boolean]
    'isShortSale' => true/false, (optional) [boolean]
    'ratificationDate' => "...", (optional) [string] ISO 8601 Date String
    'closingDate' => "...", (optional) [string] ISO 8601 Date String
    'mlsId' => "..." (optional) [string]
  ],
  'agentInformation' => [
    'represents' => "...", (optional) [string]
    'completedTransactions' => ..., (optional) [number]
    'pendingContracts' => ..., (optional) [number]
    'activeListings' => ..., (optional) [number]
    'fullName' => "...", (optional) [string]
    'email' => "...", (optional) [string] Must be a valid email
    'phoneNumber' => "...", (optional) [string] Must be a valid US phone number; Format: '+19179246228'
    'licenseNumber' => "..." (optional) [string]
  ]
]
```

`dealInformation['propertyType']` should be one of:
* 'Condo'
* 'Co-op'
* 'Single-Family'
* 'Multi-Family'
* 'Townhouse'
* 'Manufactured Home'
* 'Half Duplex'
* 'Mobile Home'


### <a name="document-upload"></a> Attaching Documents to Advance Application
The `documents` property on Integration URLs data allows attaching documents to Advance Application.
If `documents` is present, then the `application` property becomes required.

The expected data format is:
```
[
  "user" => [ ... ],
  "application" => [ ... ], (required in this case),
  "documents" => [
    [
      "type" => "..." (required) [string] Document type. Please find the list of available document types below.
      "url" => "..." (required) [string] A URL for a direct document download. HTTP GET is used to fetch the document.
    ],
    [ ... ]
  ]
]
```

The document type should be one of:
* "idDocuments"
* "contract"
* "commission_report"
* "agreement"
* "money_deposit"
* "pre_approval_letter"
* "inspection_report"
* "bank_approval"

Note: Documents are downloaded in a separate process. That happens in a background.
The agent is going to be notified that the documents are loading when he visits Payfully website.

Also if any of the document downloads fail, the agent will be able to do a manual upload at Payfully website.