<?php
namespace ApiTransferMoneyOrderPayout\Services;

class VifoAuthenticate
{
    public function login($username, $password)
    {    
        $data =  [
            'username' => $username,
            'password' => $password
        ];

        return $data;
    }
}
