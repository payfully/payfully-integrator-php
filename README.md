# Payfully Integrator PHP

 - [Introduction](#introduction)
   + [How it works](#how-it-works)
 - [Integration Links](#integration-links)
   + [Data Structure](#-data-structure)
   + [Credentials Explained](#credentials-explained)
   + [Generating Integration URLs](#api-link-generation-php)
 - [Creating User Data](#user-required-data)
 - [Creating Advance Applications](#aa-required-data)
 - [Attaching Documents to an Advance Application](#document-upload)

## Introduction

Welcome! We greatly appreciate your interest in the [Payfully](https://www.payfully.co/) platform! In this guide we are going to walk you through our integration features.  

If you have any questions please don't hesitate to [contact us](alberto@payfully.co).  

### <a name="how-it-works"></a> How it works

We provide url generator that will create an advance application with the information provided. If the user does not exist in our platform, we will automatically create it.

**Payfully has two (2) environments:**
- Production: Live environment
- Staging: Testing environment

We'll provide the testing environment keys for you, and when the integration is ready we will send the production ones.

**Instructions:**
1. You need to obtain the specific environment credentials: `relativeUrl` and `aesKey`.
2. Gather the required information (user, application, and documents).
3. Add each component to the `$urlGenerator`.
4. Generate a special URL (referred as IntegrationURL).
5. Place them on your platform, so that agents can click on them.

When the Agent (user) which is logged into your platform clicks on the link, they get redirected to Payfully, we automatically log the agent in (and create an account if needed). Finally, the Advance Application is also generated at the same time automatically. The agent will only need to fill out the information that was not provided via the link generator.

**Important:**
Integration URLs should be considered as sensitive information and
should not be exposed to anyone except the users (which they are generated for).
As following the Integration URL allows logging into Payfully account.

## <a name="integration-links"></a> Integration Links

The generated Integration URL looks like:

`https://<environment>.payfully.co/integrations/[relativeUrl]/[encodedData]`

- `**relativeUrl**`: It's the one provided by us for your specific Environment.
- `**encodedData**`: It's the **JSON data** that is first **AES Encrypted** and then **Base64 encoded**.

An example Integration URL looks like:

`https://integration-stage.payfully.co/integrations/SuperAgency/VTJGc2RHVmtYMTlDSlo0Uk16TjJKOFZNZU4rcmc3VWNobUVXMjNtQzQ4ST0=`

### <a name="data-structure"></a> Data Structure

The URLGenerator expects you to pass the following data:

```php
$urlgenerator->setUser([...]);
$urlgenerator->setApplication([...]);
$urlgenerator->setDocuments([...]);
```

Please refer to the specific sections to get more detail: 
* [user](#user-required-data)
* [application](#aa-required-data)
* [documents](#document-upload)

### Credentials Explained

In order to generate Integration URLs you will need to get the following credentials:

- `relativeUrl`: Provided by us.
- `aesKey`: - AES Key used to encrypt the data. Also, provided by us.

Please contact immediately a Payfully Admin in case it gets exposed/compromised. The Admin will rotate it and provide you with the new key.

### <a name="api-link-generation-php"></a> Generating an Integration URL

Instructions:
1. Add the library to composer.
```
composer require payfully/integrator
```
2. Instantiate the library object, and set the `relativeUrl`, `aesKey` and `Env` values inside the constructor. 
```php
require __DIR__ . '/vendor/autoload.php';

use Payfully\Integrator\UrlGenerator;
use Payfully\Integrator\Env;

$relativeUrl = 'SuperAgency';
$aesKey = "Qkoghsks1Oe3V+/s+wtV6b1FFmM+YdQCg0mGPTiO3xofssrcsgR6yA3rvsSIyq/85DiHm/7BIbrEg1GOL1soag==";
$urlgenerator = new UrlGenerator($relativeUrl, $aesKey, Env::Stage);
```
**NOTE: The possible environment values are:**
- `Env::Stage`
- `Env::Production`

*If no environment is set, then we assume it's `production`*

3. Form a PHP array containing the required [data](#data-structure) and set the Data to each section.
```php
$urlgenerator->setUser([...]);
$urlgenerator->setApplication([...]);
$urlgenerator->setDocuments([...]);
```

4. Finally call the method `generate()`
```php
echo $urlgenerator->generate();
```

Here is the full example:
```php
require __DIR__ . '/vendor/autoload.php';

use Payfully\Integrator\UrlGenerator;
use Payfully\Integrator\Env;

$relativeUrl = 'SuperAgency';
$aesKey = "Qkoghsks1Oe3V+/s+wtV6b1FFmM+YdQCg0mGPTiO3xofssrcsgR6yA3rvsSIyq/85DiHm/7BIbrEg1GOL1soag==";
$urlgenerator = new UrlGenerator($relativeUrl, $aesKey, Env::Stage);
$urlgenerator->setUser([...]);
$urlgenerator->setApplication([...]);
$urlgenerator->setDocuments([...]);

echo $urlgenerator->generate();
```

The code above outputs:
```text

https://integration-stage.payfully.co/integrations/SuperAgency/VTJGc2RHVmtYMS9EY0FEdnJ0MFdhSkJqdGlIYXB1ZVF5cWE2VkpYSXhiTT0=

```

**NOTE: Tested with PHP v7.1**

## <a id="user-required-data" /> User Data  
### Required Data

Each Integration URL must contain the user information.

```php
$user = [
  "email"=> "...", // (required) [string] Agent's email
  "fullName" => "...", // (required) [string] Agent's full name
  "phone" => "..." // (required) [string] Agent phone number; Must be a valid US phone number; Format: '+19179246228'
];
```

We are going to create a Payfully account basing on this data.

Note:
* The password is generated and send over the email along with the reset password link.
* If the user with such email already exists **and** created over the integration we just log the user in.
(e.g. that could happen if agent follow the same Integration URL twice or more times)

## <a name="aa-required-data"></a> Advance Applications Data 

If you have internal MLS ID's for deals please provide it as `dealInformation['mlsId']`. This is used to avoid duplicating Advance Applications. If `mlsId` Is missing it's going to be auto-generated by combining `dealInformation['closingDate']` and `dealInformation['propertyAddress']`.

Any optional data that was not provided by Integration URL can be filled later manually.

### Required Data

```php
$application = [
  'shareOfCommission' => ..., // (optional) [number] - Commission ammount expected by the Agent for the deal
  'dealInformation' => [
    'propertyAddress' => "...", // (optional) [string] - Address of the property
    'propertyType' => "...", // (optional) [string] - Type of property
    'isNewConstruction' => true/false, // (optional) [boolean] - Is it a new contruction?
    'isShortSale' => true/false, // (optional) [boolean] - Is it a short sale?
    'ratificationDate' => "...", // (optional) [string] ISO 8601 Date String - Date in which al contingencies are met
    'closingDate' => "...", // (optional) [string] ISO 8601 Date String - Closing date of the contract
    'mlsId' => "..." // (optional) [string] - MLS ID
  ],
  'agentInformation' => [
    'represents' => "...", // (optional) [string] - Who does the agent represents on this deal (see below)
    'completedTransactions' => ..., // (optional) [number] - Completed transactions by the agent in the last 6 months
    'pendingContracts' => ..., // (optional) [number] - Number of Agent's pending contracts at the moment
    'activeListings' => ..., // (optional) [number] - Number of Agent's active listings at the moment
    'fullName' => "...", // (optional) [string] - Full name of the agent
    'email' => "...", // (optional) [string] - Agent's email address
    'phoneNumber' => "...", // (optional) [string] - Agent's phone number
    'licenseNumber' => "..." // (optional) [string] - Agent's license number
  ]
];
```

`agentInformation['represents']` can only be one of:
* 'Listing'
* 'Buying'
* 'Both'

`dealInformation['propertyType']` should be one of:
* 'Condo'
* 'Co-op'
* 'Single-Family'
* 'Multi-Family'
* 'Townhouse'
* 'Manufactured Home'
* 'Half Duplex'
* 'Mobile Home'
* 'Other' - Provide the string

### <a name="document-upload"></a> Attaching Documents to Advance Application
The `documents` property on Integration URLs data allows attaching documents to Advance Application. If this object is empty, the agent will need to upload all documents on our platform.

The documents object looks like:
```php
$documents = [
  [
    "type" => "..." // (required) [string] Document type. Please find the list of available document types below.
    "url" => "..." // (required) [string] A URL for a direct document download. HTTP GET is used to fetch the document.
  ],
  [ ... ]
];
```

The document type should be one of:
* "idDocuments" - You can upload up to 2 documents here with the same type
* "contract" - Contract of sale
* "commission_report" - Commission report for the Agent
* "agreement" - MLS agreement for that specific listing
* "money_deposit" - Earnest Money Deposit
* "pre_approval_letter" - Pre approval letter of the buyer
* "inspection_report" - A document that contains the inspection report
* "bank_approval" - The bank approval for the mortgage

Note: Documents are downloaded in a separate process. That happens in a background.
The agent is going to be notified that the documents are loading when he visits Payfully website.

Also if any of the document downloads fail, the agent will be able to do a manual upload at Payfully website.
