<?php

use Modules\Services\VifoServiceFactory;

require 'vendor/autoload.php';
$serviceFactory = new VifoServiceFactory('dev');
// $authenticateUser = $serviceFactory->performUserAuthentication('asdsadsad', 'asdasdasd');
$user = $serviceFactory->setAdminToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijk4NTgxZmYwYTUwNjllYTljNWIxOWNjODY3OTQ1N2ZjYmQxN2E5YTAxM2I2ODFlMDQwY2QxZDhmZjkxZWM2NGM4MTgyYWYzMDAxNGExMzE2In0.eyJhdWQiOiI4IiwianRpIjoiOTg1ODFmZjBhNTA2OWVhOWM1YjE5Y2M4Njc5NDU3ZmNiZDE3YTlhMDEzYjY4MWUwNDBjZDFkOGZmOTFlYzY0YzgxODJhZjMwMDE0YTEzMTYiLCJpYXQiOjE3MjY4MTY4OTUsIm5iZiI6MTcyNjgxNjg5NSwiZXhwIjoxNzU4MzUyODk1LCJzdWIiOiIxNjEyNiIsInNjb3BlcyI6W119.A4qWvEJM8OwvRLkX36GBLQdRST-F2w7f4wXzg3a-djKUVTgiAtlsDsSTfV11SnnK_fKpolKoXnYqAWepjVX-xpcmyauEeKf6WO5MR94YrRGlZ2YQk5HXczzpfcXctmN6nlgZIizGyxo_-Yykq2cBbp0Xq0KZW9Pzimds15LsTZ6GN9CkRywgAuidTRDq2AqkHknFtfIqpL_YgBgbyhDVdHfEgRU6d1Xcgsf0TZWdnedIe0BNfY-6yaCiI6FnkevSCPScxo1xNxFP62092xXSvvMLMKVCRZkqF5iZDLkMnhEzRGbs9hcmw7QSYRzIu3a5-NkPb0RIdneRSQNgSy_JxQGcf2SmF1RAysLk6hZW2HtGPNiu4o-JTiJ4P-3f0XEZL7oqHulMSPnt0aDryooXnG9I41PcysUVxs9BFXfEyCSm8t0t9mEdDdeKCDaOUc6tlV8mrZCtPyo_Mkn2pY3Zq1AsXyTseq6j4aTHBtoxINhert9gmtnFiuXGp03i4ZeXZS2JuPgVB0XxBi7MeDT4lUg7x6-JmCiRhwnqF_yI4BKSd_-QU2c_WgeCEzIZ_e8V1qLsVfEYsX7UHznWFUfesuxoWWS1dWYCmsvsyYdZMvlLStFwv_yVgjXsKqTrmyIa7mjoX2J8Dwb4gfCYv4dl7lFmuEAiYwYCoPADR_NmKps');
$a = $serviceFactory->createSevaOrder(
    'Dai Vu',
    '970406',
    '0214599002',
    "REVAVF240101",
  "1231231231223",
   "0972640911",
    "",
    "",
     50000,
  "remark test",
    false,
  "",
   null,
);
echo '<pre>';
print_r($a);

echo'</pre>';
