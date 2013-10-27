<?php

namespace okazo\geoBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * cityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class cityRepository extends EntityRepository
{
    public function cityByCoordinates($latitude, $longitude) {
        $queryBuilder=$this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('c, GEO_DISTANCE(:originLat, :originLong, c.citylatitude, c.citylongitude) as distance');
        $queryBuilder->from('okazo\geoBundle\Entity\city', 'c');
        $queryBuilder->setParameter('originLat', $latitude);
        $queryBuilder->setParameter('originLong', $longitude);                    
        $queryBuilder->addOrderBy('distance', 'ASC');
     
        $requete=$queryBuilder->getQuery();
        
        $requete->setFirstResult(0);
        $requete->setMaxResults(1);
        
        //$requete->execute(null,\Doctrine\ORM\Query::HYDRATE_ARRAY); //Cela semble ne servir à rien, mais sans ca, le paginator ne fonctionne pas... Aucune explication là dessus.
        return $requete->execute(null,\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }
    
    public function cityByName($input, $maxRows) {
        
        //retrait des espaces avant et après la chaine
        $input=trim($input);
                
        //on remplace les caractères spéciaux par des tirets (espace, apostrophe)
        $filter = array(" ", "'");
        $input = str_replace($filter, "-", $input);
                
        $queryBuilder=$this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('c.cityname, c.citycp, c.citylongitude, c.citylatitude');
        $queryBuilder->from('okazo\geoBundle\Entity\city', 'c');
        $queryBuilder->where("c.cityurl LIKE :param");
        $queryBuilder->orWhere("c.citycp LIKE :param");
        $queryBuilder->addOrderBy('c.cityname', 'ASC');
        $queryBuilder->setParameter('param', $input.'%');
     
        $requete=$queryBuilder->getQuery();
        
        $requete->setFirstResult(0);
        $requete->setMaxResults($maxRows);
            
        return $requete->execute();
    }
    
}
