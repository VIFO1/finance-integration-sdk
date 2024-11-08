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
$environment = 'stg';
$serviceFactory = new VifoServiceFactory($environment);
$authenticateUser = $serviceFactory->performUserAuthentication(string $username, string $password);

2.1 Methods for Token Setup
$accessTokenUser = $service->setUserToken('your_user_token_here');
-This method is used to set the authentication token for user-based requests. 

$accessTokenAdmin = $service->setAdminToken('your_user_token_here');
-This method is used to set the authentication token for admin-based requests.

Using Tokens in Requests
Once the tokens are set using the above methods, they will be automatically included in the headers for their respective requests.

3.Prepare data

3.1  Get List of available Banks:
$bank= $serviceFactory ->fetchBankInformation(array $body);

3.2 Get NAPAS Beneficiary Name:
$bank= $serviceFactory ->fetchBeneficiaryName(array $body);

4.Create Transfer Money API:
$transferMoney = $serviceFactory ->executeMoneyTransfer(array $body);

5.Bulk Approve Transfer Money API

$approveMoneyTransfer=$serviceFactory->approveMoneyTransfer(string $secretKey, string $timestamp, array $body);

6.Webhook to inform the result of transfer / pay out request
$webhook = $serviceFactory->verifyWebhookSignature(array $data, string $requestSignature, string $secretKey, string $timestamp):

7. Others request
$otherRequest = $serviceFactory ->processOtherRequest(string $key);

8.Create Reva Order
$createRevaOrder = $serviceFactory-> createRevaOrder(
        string $fullname, 
        string $benefiaryAccountName,
        string $productCode = null,
        string $distributorOrderNumber,
        string $phone,
        string $email,
        string $address, 
        float $finalAmount,
        string $comment,
        bool $bankDetail,
        string $qrType = null,
        string $endDate = null
    ): array;
-Description: This method creates a new Reva order.
-Returns: An array containing the response of the order creation.

9.Create Seva Order
$createSevaOrder = $serviceFactory->createSevaOrder(
        string $productCode = null, 
        string $phone, 
        string $fullname,
        float $finalAmount, 
        string $distributorOrderNumber, 
        string $beneficiaryBankCode, 
        string $beneficiaryAccountNo,
        string $comment,
        string $sourceAccountNo
    ): array;
-Description: This method creates a new Seva order.
-Returns: An array containing the response of the order creation.