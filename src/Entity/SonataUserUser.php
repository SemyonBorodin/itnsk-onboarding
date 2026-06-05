<?php

namespace App\Entity;

use Sonata\UserBundle\Entity\BaseUser3 as BaseUser;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\Table(name: 'user__user')]
class SonataUserUser extends BaseUser
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    protected $id;
}
