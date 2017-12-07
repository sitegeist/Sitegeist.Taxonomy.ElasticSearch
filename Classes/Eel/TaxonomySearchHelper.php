<?php
namespace Sitegeist\Taxonomy\ElasticSearch\Eel;

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
     * @param string $taxonomyProperty
     * @return QueryBuilderInterface
     */
    public function taxonomyProperty($taxonomyProperty) {
        $this->taxonomyProperty = $taxonomyProperty;
        return $this;
    }

    /**
     * Match the searchword against the fulltext index
     *
     * @param string $searchWord
     * @return QueryBuilderInterface
     */
    public function fulltext($searchWord)
    {
        // create and execute a search for taxonimies first
        $subQueryBuilder = new ElasticSearchQueryBuilder();
        $taxonomyQueryResults = $subQueryBuilder->query($this->taxonomyService->getRootNode())
            ->fulltext($searchWord)
            ->nodeType(\Sitegeist\Taxonomy\Package::TAXONOMY_NODE_TYPE)
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
                                    function($taxonomyQueryResult) { return $taxonomyQueryResult->getIdentifier(); },
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
