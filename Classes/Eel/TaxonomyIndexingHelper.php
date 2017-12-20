<?php
namespace Sitegeist\Taxonomy\ElasticSearch\Eel;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Sitegeist\Taxonomy\Service\TaxonomyService;
use Neos\Eel\ProtectedContextAwareInterface;

class TaxonomyIndexingHelper implements ProtectedContextAwareInterface
{

    /**
     * @var TaxonomyService
     * @Flow\Inject
     */
    protected $taxonomyService;

    /**
     * @param NodeInterface[] $taxonomies
     */
    public function buildIdentifierList($taxonomies)
    {
        if (!$taxonomies) {
            return [];
        }

        $pathPrefixes = [];

        foreach ($taxonomies as $taxonomy) {
            $pathPrefixes[] = $taxonomy->getIdentifier();
            $parent = $taxonomy->getParent();
            while ($parent && $parent->getNodeType()->isOfType($this->taxonomyService->getTaxonomyNodeType())) {
                $pathPrefixes[] = $parent->getIdentifier();
                $parent = $parent->getParent();
            }
        }

        return array_unique($pathPrefixes);
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
