<?php
namespace App\Controller;

use App\Entity\Solr\Contract;
use Elastica\Query;
use Elastica\Query\QueryString;
use Elastica\QueryBuilder;
use Elastica\Result;
use FS\SolrBundle\Doctrine\Hydration\HydrationModes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{

    /**
     * @Route("/test/elasticsearch", name="elasticsearch")
     */
    public function elasticsearch()
    {
        $finder = $this->container->get('fos_elastica.index.tests_isow');

        $wordQuery = new QueryString("hello");

        $query = new Query($wordQuery);

        try {
            $resultSet = $finder->search($query);
        } catch(\Exception $e) {
            var_dump($e->getMessage());die();
        }

        $response = '<html><body>';

        /**
         * @var Result $result
         */
        foreach ($resultSet->getResults() as $result) {
            $response .= json_encode($result->getData()) . "\n";
        }

        $response .= '</body></html>';


        return new Response($response);
    }

    /**
     * @Route("/test/solr", name="solr")
     */
    public function solrAction()
    {
        $solrClient = $this->get('solr.client');


        try {
            $query = $solrClient->createQuery(Contract::class);
            $query->addSearchTerm('name', 'garantie*');
            $query->setHydrationMode(HydrationModes::HYDRATE_INDEX);

            $contracts = $query->getResult();
        } catch (\Exception $e) {
            var_dump($e->getMessage() . " => " . $e->getFile() . " : " . $e->getLine());die();
        }

        $response = '<html><body>';

        $response .= "<table border='1'>";
        $response .= "<tbody>";
        /**
         * @var Contract $contract
         */
        foreach ($contracts as $contract) {
            $response .= "<tr>";
            $response .= "<td>" . $contract->getId() . "</td>";
            $response .= "<td>" . $contract->getName() . "</td>";
            $response .= "<td>" . $contract->getGamme() . "</td>";
            $response .= "<td>" . $contract->getIsFormula() . "</td>";
            $response .= "<td>" . $contract->getFormulaPosition() . "</td>";
            $response .= "<td>" . $contract->getOnSale()[0] . "</td>";
            $response .= "<tr>";
        }

        $response .= "</tbody>";
        $response .= "</table>";

        $response .= '</body></html>';


        return new Response($response);
    }

}