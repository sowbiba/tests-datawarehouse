<?php

namespace App\Solr\Query;

class SolrQuery extends \FS\SolrBundle\Query\SolrQuery
{

    /**
     * @var bool
     */
    private $useAndOperator = false;

    /**
     * @var bool
     */
    private $useWildcards = false;

    /**
     * @param bool $strict
     */
    public function setUseAndOperator($strict)
    {
        $this->useAndOperator = $strict;
    }

    /**
     * @param bool $boolean
     */
    public function setUseWildcard($boolean)
    {
        $this->useWildcards = $boolean;
    }

    public function getQuery()
    {
        $keyField = $this->getMetaInformation()->getDocumentKey();

        $documentLimitation = $this->createFilterQuery('id')->setQuery('id:*');

        $this->addFilterQuery($documentLimitation);
        if ($this->getCustomQuery()) {
            parent::setQuery($this->getCustomQuery());

            return $this->getQuery();
        }

        $term = '';
        // query all documents if no terms exists
        if (count($this->getSearchTerms()) == 0) {
            $query = '*:*';
            parent::setQuery($query);

            return $query;
        }

        $logicOperator = 'AND';
        if (!$this->useAndOperator) {
            $logicOperator = 'OR';
        }

        $termCount = 1;
        foreach ($this->getSearchTerms() as $fieldName => $fieldValue) {

            if ($fieldName == 'id') {
                $this->getFilterQuery('id')->setQuery('id:' . $fieldValue);

                $termCount++;

                continue;
            }

            $fieldValue = $this->querifyFieldValue($fieldValue);

            $term .= $fieldName . ':' . $fieldValue;

            if ($termCount < count($this->getSearchTerms())) {
                $term .= ' ' . $logicOperator . ' ';
            }

            $termCount++;
        }

        if (strlen($term) == 0) {
            $term = '*:*';
        }

        $this->setQuery($term);

        return $term;
    }

    /**
     * Transforms array to string representation and adds quotes
     *
     * @param string $fieldValue
     *
     * @return string
     */
    private function querifyFieldValue($fieldValue)
    {
        if (is_array($fieldValue) && count($fieldValue) > 1) {
            sort($fieldValue);

            $quoted = array_map(function($value) {
                return '"'. $value .'"';
            }, $fieldValue);

            $fieldValue = implode(' TO ', $quoted);
            $fieldValue = '['. $fieldValue . ']';

            return $fieldValue;
        }

        if (is_array($fieldValue) && count($fieldValue) === 1) {
            $fieldValue = array_pop($fieldValue);
        }

        if ($this->useWildcards) {
            $fieldValue = '*' . $fieldValue . '*';
        }

        $termParts = explode(' ', $fieldValue);
        if (count($termParts) > 1) {
            $fieldValue = '"'.$fieldValue.'"';
        }

        return $fieldValue;
    }

}