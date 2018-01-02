<?php
namespace Sitegeist\Taxonomy\ElasticSearch\Eel;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Flowpack\ElasticSearch\ContentRepositoryAdaptor\Eel\ElasticSearchQueryBuilder;
use Sitegeist\Taxonomy\Service\TaxonomyService;

class TaxonomySearchHelper extends ElasticSearchQueryBuilder
{
    /**
     * @var TaxonomyService
     * @Flow\Inject
     */
    protected $taxonomyService;

    /**
     * @var string
     */
    protected $taxonomyProperty = 'taxonomyReferences';

    /**
     * @var string
     */
    protected $taxonomyStartingPoint;

    /**
     * @param string $taxonomyProperty
     * @return QueryBuilderInterface
     */
    public function taxonomyProperty($taxonomyProperty)
    {
        $this->taxonomyProperty = $taxonomyProperty;
        return $this;
    }

    /**
     * @param  string $taxonomyStartingPoint
     */
    public function taxonomyStartingPoint($taxonomyStartingPoint)
    {
        $this->taxonomyStartingPoint = $taxonomyStartingPoint;
        return $this;
    }

    /**
     * Match the searchword against the fulltext index for taxonomies and the current site
     *
     * @param string $searchWord
     * @return QueryBuilderInterface
     */
    public function fulltext($searchWord)
    {
        /**
         * @var NodeInterface $rootNode
         */
        $rootNode =$this->taxonomyService->getRoot();

        if ($this->taxonomyStartingPoint) {
            $rootNode = $rootNode->getNode($this->taxonomyStartingPoint);
        }

        if (!$rootNode) {
            return parent::fulltext($searchWord);
        }

        // create and execute a search for taxonimies first
        $subQueryBuilder = new ElasticSearchQueryBuilder();
        $taxonomyQueryResults = $subQueryBuilder->query($rootNode)
            ->fulltext($searchWord)
            ->nodeType($this->taxonomyService->getTaxonomyNodeType())
            ->execute()
            ->toArray();

        if (count($taxonomyQueryResults) == 0) {
            return parent::fulltext($searchWord);
        }

        $this->request->appendAtPath(
            'query.filtered.filter.bool.must',
            [
                'bool' => [
                    'should' => [
                        [
                            'query_string' => [
                                'query' => $searchWord
                            ]
                        ],
                        [
                            'terms' => [
                                $this->taxonomyProperty => array_map(
                                    function ($taxonomyQueryResult) {
                                        return $taxonomyQueryResult->getIdentifier();
                                    },
                                    $taxonomyQueryResults
                                )
                            ]
                        ]
                    ]
                ]
            ]
        );

        $this->request->highlight(150, 2);

        return $this;
    }

    /**
     * @param string $methodName
     * @return bool
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
