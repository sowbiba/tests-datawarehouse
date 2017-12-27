<?php
namespace App\Controller;

use Elastica\Query;
use Elastica\Query\QueryString;
use Elastica\QueryBuilder;
use Elastica\Result;
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
        $number = mt_rand(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }

}