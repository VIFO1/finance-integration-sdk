<?php

namespace Modules\Interfaces;

interface VifoBankInterface
{
    public function getBank(array $headers): array;
    public function getBeneficiaryName(array $headers, array $body): array;
}
