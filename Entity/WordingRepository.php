<?php

namespace TranslateBundle\Entity;
use Doctrine\ORM\EntityRepository;

class WordingRepository  extends EntityRepository
{


    public function countTranslateByDomain(Domain $domain)
    {
        $query = $this->createQueryBuilder('wording')
            ->where('wording.domain = :domain')
            ->setParameter('domain', $domain)
            ->getQuery();


        return $query->select('COUNT(wording)')
                ->getQuery()
                ->getSingleScalarResult();
    }

}
