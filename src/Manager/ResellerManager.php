<?php


namespace App\Manager;


use App\Entity\Reseller;
use App\Repository\ResellerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResellerManager
{

    private EntityManagerInterface $manager;
    private ResellerRepository $resellerRepository;
    private UserPasswordEncoderInterface $encoder;

    public function __construct(EntityManagerInterface $manager, ResellerRepository $resellerRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->manager = $manager;
        $this->resellerRepository = $resellerRepository;
        $this->encoder = $encoder;
    }

    public function  findByEmail(string $email): ?Reseller
    {
        $user = $this->resellerRepository->findByEmail($email);
        if($user){
            return $user[0];
        }
        return null;
    }

    public function  findByName(string $name): ?Reseller
    {
        $user = $this->resellerRepository->findByName($name);
        if($user){
            return $user[0];
        }
        return null;
    }

    public function registerAccount(Reseller $reseller): array
    {
        if($this->findByEmail($reseller->getEmail()) || $this->findByName($reseller->getName())){
            throw new BadRequestHttpException('This reseller is already exist ! ');
        }

        $reseller->setName($reseller->getName())
                 ->setEmail($reseller->getName().'@gmail.com');
        $password = $this->encoder->encodePassword($reseller, $reseller->getPassword());
        $reseller->setPassword($password);

        $this->manager->persist($reseller);
        $this->manager->flush();

        return [
          "message" => "Reseller created",
          "reseller" => $reseller
        ];
    }


}