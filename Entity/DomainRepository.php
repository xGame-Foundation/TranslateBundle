<?php

namespace TranslateBundle\Entity;
use Doctrine\ORM\EntityRepository;

class DomainRepository  extends EntityRepository
{

    public function countTranslation(Domain $domain)
    {
        return ;
    }

}
