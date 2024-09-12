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
$authenticateUser = $serviceFactory->performUserAuthentication(string $username, string $password);

3.Prepare data

3.1  Get List of available Banks:
$bankService = $serviceFactory ->fetchBankInformation(string $accessToken, array $body);

3.2 Get NAPAS Beneficiary Name:
$bankService = $serviceFactory ->fetchBeneficiaryName(string $accessToken, array $body);

4.Create Transfer Money API:
$transferMoneyService = $serviceFactory ->executeMoneyTransfer(string $accessToken, array $body);

5.Bulk Approve Transfer Money API

$approveTransferMoneyService=$serviceFactory->approveMoneyTransfer(string $accessToken, string $secretKey, string $timestamp, array $body);

6.Webhook to inform the result of transfer / pay out request
$webhookService = serviceFactory->verifyWebhookSignature(array $data, string $requestSignature, string $secretKey, string $timestamp):

7. Others request
$otherRequestService = $serviceFactory ->processOtherRequest(string $accessToken, string $key);