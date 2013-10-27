<?php

namespace okazo\annoncesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * entitésRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class annoncesRepository extends EntityRepository
{        
    public function normalisePage($page=null)
    {
        if ($page==null) {$page=1;}
        if ($page<1) {$page=1;}
        //voir pour faire un test de non dépassement de la page maximum
    }
    
    public function normaliseGeo(&$longitude=-1000, &$latitude=-1000, &$distance=null)
    {        
        $longitude=(float)$longitude;
        $latitude=(float)$latitude;
        //$distance=(int)$distance;        
        
        if (!isset($latitude)||!isset($longitude)||$latitude==-1000||$longitude==-1000)
        {              
            $latitude=0;
            $longitude=0;
            $distance=45000;            
        }
     
        //validation et normalisation de la distance
        if ($distance==null) {$distance=1;}  //la circonférence de la terre est de 40075Km, en prenant 45000, on prendra toutes les distances de la terre
        //var_dump($distance);
        if ($distance<0) {$distance=45000;}         //0 pour ne prendre que les annonces avec la même ville                
    }
    
    public function getClassifieds($CategorieId, $page=null, $query=null, $longitudeOrigine=-1000, $latitudeOrigine=-1000, $distance=null, $sortby=null, $sortdirection=null)
    {
        //validation de la page
        //if ($page==null) {$page=1;}
        //if ($page<1) {$page=1;}
        //voir pour faire un test de non dépassement de la page maximum        
        $this->normalisePage($page);
        $this->normaliseGeo($longitudeOrigine, $latitudeOrigine, $distance);
        $listeCategorieId=$this->getCategoriesSelectionnees($CategorieId);
                
        //Gestion des tris et de leur ordre de tri
        if(strtolower($sortdirection)=="asc") {
            $ordre="ASC";
        } elseif (strtolower($sortdirection)=="desc") {
           $ordre="DESC"; 
        } else {
            $ordre="ASC";
        }        
        if (strtolower($sortby)=="distance") {
            $tri="distance";
        } elseif (strtolower($sortby)=="prix") {
            $tri="a.prix";
        } else {
            $tri="a.horodatageparse";
            $ordre="DESC";
        }
                
        // Gestion de la chaine recherchée par l'utilisateur        
        $strLike="";
        if (!$query==null) {
            $queryCleaned=str_replace(".", " ",$query);
            $queryCleaned=str_replace(",", " ",$queryCleaned);
            $queryCleaned=str_replace("-", " ",$queryCleaned);
            //supprimer les espaces consécutifs
            
            //supprimer les apostrophes
            
            $listeMots=explode(" ",$queryCleaned); //séparation de mots
            //nettoyer les caractères uniques ou doubles (on ne recherchera donc que les chaines>3 caractères)
            $nombreMots=count($listeMots);            
            $i=0;            
            foreach ($listeMots as $mot) {
                $i=$i+1;
                $strLike=$strLike."a.titre LIKE '%".$mot."%' OR ";
                $strLike=$strLike."a.texte LIKE '%".$mot."%'";                
                if ($i<$nombreMots) {$strLike=$strLike." AND ";}                
            }            
        }
        
        //Préparation de la chaine SQL de la requête
        $sqlSelect='SELECT 
                    a.id, a.titre, a.texte, a.lien, a.prix, a.horodatageparse, a.categorie_id, 
                     c.id AS cityid, c.cityname, c.citycp, 
                     s.name AS sourcename, s.baseurl AS sourceurl, 
                     12756 * ASIN(SQRT(POWER(SIN((:latOrigin - c.citylatitude) * PI()/360), 2) + COS(:latOrigin * PI()/180) * COS(c.citylatitude * PI()/180) * POWER(SIN((:longOrigin - c.citylongitude) * PI()/360), 2))) AS distance ';
        $sqlFrom='FROM annonces a ';
        $sqlLeftJoin='LEFT JOIN city c ON a.cityid = c.id ';
        $sqlLeftJoin2='LEFT JOIN sources s on a.websiteId = s.id ';
        if($strLike!='') {
            $sqlWhere='WHERE ('.$strLike.') AND a.existe=TRUE ';
        } else {
            $sqlWhere='WHERE a.existe=TRUE ';
        }
        
        //Catégories
        $INCount=0;         //comptage des valeurs dans la clause IN
        $INSetted=false;    //on a défini la clause IN pour les catégories        
        if (isset($listeCategorieId)) {            
            foreach($listeCategorieId as $categorieId) {
                if($categorieId!=0 && !empty($categorieId)) {
                    //var_dump($categorieId);
                    if ($INSetted==false) {
                        $sqlWhere=$sqlWhere." AND a.categorie_id IN(";
                        $INSetted=true;
                    }
                    if ($INCount>0) {$sqlWhere=$sqlWhere.", ";}
                    $sqlWhere=$sqlWhere.$categorieId;
                    $INCount=$INCount+1;
                }
            }
            if($INSetted==true) {
                $sqlWhere=$sqlWhere.")";
            }
        }
        
        $sqlHaving='HAVING distance <= :rayon ';        
        $sqlOrderBy='ORDER BY '.$tri.' '.$ordre.' ';
        $sqlLimit='LIMIT 10 ';
        $sqlOffset='OFFSET '.(($page-1)*10).' ';
                        
        /* Exécution de la requête principale */
        $stmt = $this->getEntityManager()
                     ->getConnection()                                          
                     ->prepare($sqlSelect.$sqlFrom.$sqlLeftJoin.$sqlLeftJoin2.$sqlWhere.$sqlHaving.$sqlOrderBy.$sqlLimit.$sqlOffset);
        $stmt->bindValue('latOrigin', $latitudeOrigine);
        $stmt->bindValue('longOrigin', $longitudeOrigine);     
        $stmt->bindValue('rayon', $distance);
      
        $stmt->execute();            
        $annonces=$stmt->fetchAll(\PDO::FETCH_ASSOC);
            
      //Insertion des images pour chaque annonce dans le tableau des annonces        
      if (count($annonces)>0) 
      {
        //Mémorisation des identifiants d'annonce qui seront affichés      
        foreach ($annonces as $annonce) {        
            $annoncesIds[]=$annonce['id'];
        }               
          $q="SELECT i.*
              FROM images i
              WHERE i.annonceId IN (";
          foreach($annoncesIds as $id) {          
              $q=$q."'".$id."',";
          }
          $q=rtrim($q,",");
          $q=$q.')';

          $stmt = $this->getEntityManager()
                         ->getConnection()
                         ->prepare($q);      
          $stmt->execute();
          $images=$stmt->fetchAll();     

          $res=array();
          foreach($annonces as $annonce){                           //pour chaque annonce
              $listeImages=array();                                 //Init vierge de la liste d'images
              foreach($images as $image){
                  if (array_search($annonce['id'], $image)!=false){ //on recherche les images contenant la liaison avec l'id de l'annonce
                    $listeImages[]=$image;                          //si on a trouvé, on mémorise
                  }              
              }          
              $annonce['images']=$listeImages;                      //ajoute les images a la fin du tableau          
              $res[]=$annonce;                                      //ajoute l'annonce dans le tableau de résultats de la boucle
          }      
          $annonces=$res;   //on remplace le tableau annonces sans les images par le resultat avec les images
      }
      //Fin d'insertion des images associées aux annonces
      
      /* Comptage des annonces */      
      if (count($annonces)>0) 
      {        
        /* Comptage du nombre total des annonces (sans limit ni offset ni order by)*/      
        $sqlSelect='SELECT 12756 * ASIN(SQRT(POWER(SIN((:latOrigin - c.citylatitude) * PI()/360), 2) + COS(:latOrigin * PI()/180) * COS(c.citylatitude * PI()/180) * POWER(SIN((:longOrigin - c.citylongitude) * PI()/360), 2))) AS distance ';        
          
        $stmt = $this->getEntityManager()
                     ->getConnection()                                          
                     ->prepare($sqlSelect.$sqlFrom.$sqlLeftJoin.$sqlWhere.$sqlHaving);
        $stmt->bindValue('latOrigin', $latitudeOrigine);
        $stmt->bindValue('longOrigin', $longitudeOrigine);     
        $stmt->bindValue('rayon', $distance);
      
        $stmt->execute();            
        $nombreAnnonces=$stmt->rowCount();        
        $nombrePages=ceil($nombreAnnonces/10);
      }
      else 
      {
        $nombreAnnonces=0;
        $nombrePages=0;
      }
      
      return array(
          "nombreAnnonces"=>$nombreAnnonces,
          "nombrePages"=>$nombrePages,
          "annonces"=>$annonces,
          "page"=>$page
      );
    }   
    
    public Function getClassifiedsPaginator($listeCategorieId, $page=null, $query=null, $longitudeOrigine=-1000, $latitudeOrigine=-1000, $distance=null)
    {
        var_dump('getClassifiedsPaginator appelé');
        $this->normalisePage($page);
        $this->normaliseGeo($longitudeOrigine, $latitudeOrigine, $distance);
        
        // Gestion de la chaine recherchée par l'utilisateur        
        $strLike="";
        if (!$query==null) {
            $queryCleaned=str_replace(".", " ",$query);
            $queryCleaned=str_replace(",", " ",$queryCleaned);
            $queryCleaned=str_replace("-", " ",$queryCleaned);
            //supprimer les espaces consécutifs
            
            //supprimer les apostrophes
            
            $listeMots=explode(" ",$queryCleaned); //séparation de mots
            //nettoyer les caractères uniques ou doubles (on ne recherchera donc que les chaines>3 caractères)
            $nombreMots=count($listeMots);            
            $i=0;            
            foreach ($listeMots as $mot) {
                $i=$i+1;
                $strLike=$strLike."a.texte LIKE '%".$mot."%'";
                if ($i<$nombreMots) {$strLike=$strLike." AND ";}
            }            
        }
        
        //Préparation de la chaine SQL de la requête        
        $sqlFrom='FROM annonces a ';
        $sqlLeftJoin='LEFT JOIN city c ON a.cityid = c.id ';        
        if($strLike!='') {
            $sqlWhere='WHERE ('.$strLike.') AND a.existe=TRUE ';
        } else {
            $sqlWhere='WHERE a.existe=TRUE ';
        }
        
        //Catégories
        $INCount=0;         //comptage des valeurs dans la clause IN
        $INSetted=false;    //on a défini la clause IN pour les catégories
        if (isset($listeCategorieId)) {            
            foreach($listeCategorieId as $categorieId) {
                if($categorieId!=0 && !empty($categorieId)) {
                    //var_dump($categorieId);
                    if ($INSetted==false) {
                        $sqlWhere=$sqlWhere." AND a.categorie_id IN(";
                        $INSetted=true;
                    }
                    if ($INCount>0) {$sqlWhere=$sqlWhere.", ";}
                    $sqlWhere=$sqlWhere.$categorieId;
                    $INCount=$INCount+1;
                }
            }
            if($INSetted==true) {
                $sqlWhere=$sqlWhere.")";
            }
        }
        
        $sqlHaving='HAVING distance <= :rayon ';                
                                
           
      /* Comptage des annonces */
            
        /* Comptage du nombre total des annonces (sans limit ni offset ni order by)*/  
        $sqlSelect='SELECT 12756 * ASIN(SQRT(POWER(SIN((:latOrigin - c.citylatitude) * PI()/360), 2) + COS(:latOrigin * PI()/180) * COS(c.citylatitude * PI()/180) * POWER(SIN((:longOrigin - c.citylongitude) * PI()/360), 2))) AS distance ';        
          
        $stmt = $this->getEntityManager()
                     ->getConnection()                                          
                     ->prepare($sqlSelect.$sqlFrom.$sqlLeftJoin.$sqlWhere.$sqlHaving);
        $stmt->bindValue('latOrigin', $latitudeOrigine);
        $stmt->bindValue('longOrigin', $longitudeOrigine);     
        $stmt->bindValue('rayon', $distance);
      
        $stmt->execute();            
        $nombreAnnonces=$stmt->rowCount();
        ////$nombreAnnonces=count($annonces);     
        $nombrePages=ceil($nombreAnnonces/10);
      
      return array(
          "nombreAnnonces"=>$nombreAnnonces,
          "nombrePages"=>$nombrePages,          
          "page"=>$page
      );
    }
    
    /**
     * a partir de l'id de ctégorie, retourne un array de tous les identifiants de catégories à utiliser lors d'une requête
     * (tout, une seule catégorie, ou lors de la sélection de la catégorie parente, retourne toutes les catégories enfant)
     */
    public function getCategoriesSelectionnees($getCategorieId=0) {
        
        $em = $this->getEntityManager(); //initialisation de l'entitymanager
        

        $resultatsRequete = $em->getRepository('okazoannoncesBundle:Annonces')->getCategoriesExistantes();
        $listeCategories = $resultatsRequete["categories"];
        
        //Normalisation catégories
        if (!isset($getCategorieId)) {
            $categorieId = 0;
        } elseif (empty($getCategorieId)) {
            $categorieId = 0;
        } elseif (!is_numeric($getCategorieId)) {
            $categorieId = 0;
        } else {
            $categorieId = $getCategorieId;
        }
        //si une catégorie sélectionnée est parente, on va chercher toutes les catégories filles
        foreach ($listeCategories as $categorieParente) {
            if ($categorieParente['id'] == $categorieId) { //on recherche la catégorie dans la liste
                if ($categorieParente['categorie_parente_id'] === \NULL) { //on vérifie si on a sélectionné une catégorie principale (pas de parents)                    
                    foreach ($listeCategories as $categorieFille) {
                        if ($categorieFille['categorie_parente_id'] == $categorieParente['id']) {
                            $listeCategoriesId[] = $categorieFille['id'];
                        }
                    }
                }
            }
        }
        if (!isset($listeCategoriesId)) {        //si on a une catégorie parente sans catégorie fille, on va chercher la catégorie parente directement.
            $listeCategoriesId[] = $getCategorieId;
        }
        
        return $listeCategoriesId;
        
    }
    
    /**
     * Retroune la liste des catégories existantes, triées par ordre hiérarchique (parents-enfants)
     */
    public function getCategoriesExistantes()
    {        
        $stmt = $this->getEntityManager()
                     ->getConnection()                                          
                     ->prepare("SELECT * FROM categories WHERE existe=TRUE");      
        $stmt->execute();            
        $tmpCategories=$stmt->fetchAll();
        
        //var_dump($tmpCategories);
        //var_dump("<br/><br/>********************<br/><br/>");
        
        $categories=[];
        
        foreach($tmpCategories as $categorie) {
            if($categorie['categorie_parente_id']==null) {
                $categories[]=$categorie;   //mémorisation de la catégorie parente
                foreach($tmpCategories as $subCategorie) {
                    if($subCategorie['categorie_parente_id']==$categorie['id']) {
                        $categories[]=$subCategorie;   //mémorisation de la sous catégorie enfant
                    }
                }
            }
        }
        
        //var_dump($categories);
        
        return array("categories"=>$categories);
        
    }
}