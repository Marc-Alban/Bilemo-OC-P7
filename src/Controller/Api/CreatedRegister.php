<?php


namespace App\Controller\Api;

use App\Entity\Reseller;
use App\Manager\ResellerManager;

class CreatedRegister
{
    private ResellerManager $resellerManager;

    public function __construct(ResellerManager $resellerManager)
    {
        $this->resellerManager = $resellerManager;
    }

    public function __invoke(Reseller $data)
    {
        $this->resellerManager->registerAccount($data);
    }

}