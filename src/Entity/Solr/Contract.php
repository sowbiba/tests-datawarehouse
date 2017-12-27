<?php
namespace App\Entity\Solr;

use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * Class Contract
 * @Solr\Document(index="premium_contract")
 */
class Contract
{
    /**
     * @Solr\Id
     */
    private $id;

    /**
     * @Solr\Field()
     */
    private $name;

    /**
     * @Solr\Field()
     */
    private $gamme;

    /**
     * @Solr\Field()
     */
    private $isFormula;

    /**
     * @Solr\Field()
     */
    private $formula_position;

    /**
     * @Solr\Field()
     */
    private $onsale;


    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getGamme()
    {
        return $this->gamme;
    }

    public function getIsFormula()
    {
        return $this->isFormula;
    }

    public function setIsFormula($isFormula)
    {
        $this->isFormula = $isFormula;
    }

    public function getFormulaPosition()
    {
        return $this->formula_position;
    }

    public function setFormulaPosition($formulaPosition)
    {
        $this->formula_position = $formulaPosition;
    }

    public function getOnSale()
    {
        return $this->onsale;
    }

}