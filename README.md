# finance-integration-sdk
PHP SDK finance of VIFO
# PHP File Usage Guide
## Purpose

This PHP file uses services from `VifoServiceFactory` to perform banking, money transfer and other requests. The following guide provides detailed information on how to use and understand the functions of the code.

## Requirements
- **PHP**: Version 7.4 or higher.
- **Composer**: Installed and configured to load dependencies.


## Code Structure
### 1. Import Classes and Requirements Automatically
```php
use ApiTransferMoneyOrderPayout\Services\VifoServiceFactory;

2.Login
$serviceFactory = new VifoServiceFactory('*');
$login = $serviceFactory->checkAuthenticateUser($headers,$username.$password);

3.Prepare data

3.1  Get List of available Banks:
$bank = $serviceFactory ->checkGetBank($headers,$body);

3.2 Get NAPAS Beneficiary Name:
$bank = $serviceFactory ->checkGetBeneficiaryName($headers,$body);

4.Create Transfer Money API:
$transfer = $serviceFactory ->checkTransferMoney($headers,$body);

5.Bulk Approve Transfer Money API

$approveTransferMoney=$serviceFactory->checkApproveTransferMoney($accessToken, $secretKey,  $timestamp, $body);

6.Webhook to inform the result of transfer / pay out request
$webhook = serviceFactory->checkWebhook( $data,  $requestSignature,  $secretKey,  $timestamp):

7. Others request
$otherRequest = $serviceFactory ->checkOtherRequest($headers,$key);