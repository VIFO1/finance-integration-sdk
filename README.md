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
use App\Services\VifoServiceFactory;
require 'vendor/autoload.php';

2.Login
$login = new VifoServiceFactory('*');
$login->login($headers,$username,$password);

3.Prepare data

3.1  Get List of available Banks:
$bank = new VifoBank($headers);
$bank->getBank($body);

3.2 Get NAPAS Beneficiary Name:
$bank->getBeneficiaryName($body);

4.Create Transfer Money API:
$transfer = new VifoTransferMoney($headers);
$transfer->createTransferMoney($body);

5.Bulk Approve Transfer Money API

$approveTransferMoney = new VifoApproveTransferMoney($headers);
$approveTransferMoney->approveTransfers($secretKey,$timestamp,$body);


6.Webhook to inform the result of transfer / pay out request
$webhook = new Webhook();
$webhook->handle($data, $requestSignature, $secretKey);

7. Others request
$otherRequest =  new VifoOtherRequest($headers);
$otherRequest->checkOrderStatus($key);