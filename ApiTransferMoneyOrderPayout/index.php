<?php
require "vendor/autoload.php";

use Modules\Services\VifoServiceFactory;

use function Modules\CommonFunctions\generateSignature;


$service = new VifoServiceFactory('dev');
// $login = $service->performUserAuthentication("NEWSPACE_sale_demo","newspace@vifo123");
// print_r($login);
// $tokenAdmin = $service->setAdminToken("eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijc0YTI1OWRjMjIzZWQ1NGJjNGNiY2JkMDA0ZjM3ZmZhZDQ0ZTY0MmM0MDljODZmMzAzODczMzBkNGE2MzYzYmVjZWE2NTNiZDY3ZmEzMWFlIn0.eyJhdWQiOiI4IiwianRpIjoiNzRhMjU5ZGMyMjNlZDU0YmM0Y2JjYmQwMDRmMzdmZmFkNDRlNjQyYzQwOWM4NmYzMDM4NzMzMGQ0YTYzNjNiZWNlYTY1M2JkNjdmYTMxYWUiLCJpYXQiOjE3Mjc3NTg0NDEsIm5iZiI6MTcyNzc1ODQ0MSwiZXhwIjoxNzU5Mjk0NDQxLCJzdWIiOiIxNjEyNyIsInNjb3BlcyI6W119.na15o12gl8H38ox7VPGtNnRlnhXtrDGBYgj8NppdYWbXs8fCWvRHOIP41ZZKFLF0l24XZ5GYflfKG4PK-rRlV-vlE2XVWqz0w5qWwVS11g6WVRUZgmjVsKfxu9YHCTdYWnYPW_cyQ91fYK0ldTjJRWCwB0aNKmd6zh23cuWQ2U4htfLQbgQqjkE_pLqp5ROgauTPoFD79gulljD6bOdXnhh82pLQ0VSqvbIh86xZo0lCiInLAsQlX-X8UJb2qv4U_5n57eZNwVe-D4BxMlb_rn-zGbKqKuJw0FOvibtqUO7VLEiaCS_13uqSw2EcYlsXN4BG-00Kx7IfW-5TsibKxWAlDOGNdW8TnphAJVjYq7gBdbNGE_UNoMpO-VdmHpIzhvJ4rMK3Yh_jGwdyP8gZHAGk1Z3QPx3PneN2SqREbCKDbbdHdlrXFPQityPwUmnKZ5MF79nGxevB29QaEQlEd2mG6nwdX1KzbuqrgqlA166a1rcJJrQgPEMD-6RcDweWI42mHmhuiWfMZNtS3EHT-aUqw6l_RmW9cbg185ijiDS4to1QhwgTie-Rh4vQFPFpintGI-MHMaJ-p7TuzJArpJRQXMRlXud45MdV8ihmrYuH5o65C8MrL87RObq6-_lMDa1v-qz2t-tv-_pCcgrU4pjsDIZ1gG52zRKRft_Ma9A");
$user = $service->setUserToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijc2NDMwMzI5YmYxNzgxNWUwZjgyNWRiMzgwNDRiZmMwMmM4NTNkZmM1N2NkMmY4YmE3OTc1YmRkNzkwNDEyODgzZWM3MWM5MDYxMzQxMDExIn0.eyJhdWQiOiI4IiwianRpIjoiNzY0MzAzMjliZjE3ODE1ZTBmODI1ZGIzODA0NGJmYzAyYzg1M2RmYzU3Y2QyZjhiYTc5NzViZGQ3OTA0MTI4ODNlYzcxYzkwNjEzNDEwMTEiLCJpYXQiOjE3Mjk4Mzc2MzgsIm5iZiI6MTcyOTgzNzYzOCwiZXhwIjoxNzYxMzczNjM4LCJzdWIiOiIxNjEyNiIsInNjb3BlcyI6W119.e57m7wbnEJ_owkyyALpK8OCKI2_JXPb01S8pbycBwxsopjDOvLK1OSp3TiVXDlwWq-L08kBun5eIEsOef4Uq-_MZd6iEStkHaFfIb4prUI3zeDzN40Hf9iGa89NwVy7EOeRW600DQwPEa4aGb_2jW610jOmnwaowfKZIYySj6x8S4j0lba-n5odBuTdzPkPlzATik9n_lDxQz_IYUf8g9595xbehOCbgvkdrSGgp3uD1f4xB_r_-I2qt090x4h62w-cXkmqHourN-d8PM5-Z_LsUATpSWQo4SR9J_Vev50D40NwCXm6mBsd6Q8saXEYE-6f86ynoAPp6T6cf5oDEk1sF_ZhZjyDTWObAODMsg7bMEm8WfPqwSX8VxhAbznQfTr7RWX-FlkcVwNSvT4MwzEawIwpdb8kwuP82sA686d45tEgPgyz7zvaaYFTdz--6RGonn9ZyRZqL4H4qICofqMeoqtW7DUvksI_AypJLIEk4Y8Um5EU2SqO2gh0IovC5nJkQVf-0un6eOXXHPtNWIHZDBkvUEyCLFdbzsIpH-ME8aHNjRiZOv5tzBFNTpFEbru8llD29zCchIpwuhfKieo2RC-BS-U7Iz3egL60ZN5G1Hq8STo5Ek_vzbEKpZKKTi0IJF_mjH4kZZgshpGk0spUhROPM5ODSlnexecbK4xM');

// $secretKey = "Uz7xYpuH0DFcET8NZ9egdhCujJzJvYl2";
// $timestamp = "2023-11-17 10:00:00";
// $body = [
//     "status_code"=>6,
//     "ids"=> ['asdjasd']
// ];
// $secretKey = "O3q9HmIYCz5RWgDkklnG4f4ErPiTzQLw";
// $timestamp = "2024-10-24 12:30:24.000000";
// $body = [
//     "status_code"=>6,
//     "ids"=> ['mg6q36k0rw85b8kz']
// ];
// $test = generateSignature($body,$secretKey,$timestamp);
// echo $test;
// $appro = $service->approveMoneyTransfer($secretKey, $timestamp,$body);
// $data = array(
//     "product_code" => "SEVAVF240101",
//     "phone" => "01235324",
//     "fullname" => "Dai Vu",
//     "final_amount" => 100000,
//     "distributor_order_number" => "XXXXXXX231",
//     "beneficiary_bank_code" => "970406",
//     "beneficiary_account_no" => "0214599002",
//     "comment" => "Dai test",
//     "source_account_no" => "543534253425"
// );
// $a = $service->executeMoneyTransfer($data);
// $a  = $service->processOtherRequest("VF240118001025243");
// print_r($a);

$test = $service->createRevaOrder(
    'trung',                // fullname
    'VIFO VA TEST',                // beneficiaryAccountName
    null,                          // productCode (nullable)
    "123",                            // distributorOrderNumber
    "123213",                   // phone
    "daicmngia@gmail.com",                            // email
    "",                            // address
    123,                       // finalAmount (float)
    "asd",                   // comment
    false,                        // bankDetail (bool)
    null,                         // qrType (nullable)
    null                          // endDate (nullable)
);

echo "<pre>";
print_r($test);
echo "</pre>";

// $test = $service->createSevaOrder(
//   null,
//   "123213",                   // phone
//   "Dai Vu",
//   199999,
//   "XXXXXXX146",
//   "970406",
//   "0214599002",
//   "Dai test",
//   "543534253425",
// );

// echo "<pre>";
// print_r($test);
// echo "</pre>";
