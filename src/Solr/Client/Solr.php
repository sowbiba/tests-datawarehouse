<?php
namespace App\Solr\Client;
use App\Solr\Query\SolrQuery;

class Solr extends \FS\SolrBundle\Solr
{


    /**
     * @param object|string $entity entity, entity-alias or classname
     *
     * @return SolrQuery
     */
    public function createQuery($entity)
    {
        $metaInformation = $this->metaInformationFactory->loadInformation($entity);

        $query = new SolrQuery();
        $query->setSolr($this);
        $query->setEntity($metaInformation->getClassName());
        $query->setIndex($metaInformation->getIndex());
        $query->setMetaInformation($metaInformation);
        $query->setMappedFields($metaInformation->getFieldMapping());

        return $query;
    }

}