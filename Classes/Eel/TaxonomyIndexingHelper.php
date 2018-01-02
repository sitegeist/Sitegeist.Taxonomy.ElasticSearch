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
     * @param NodeInterface|NodeInterface[] $taxonomies
     * @return array
     */
    public function extractPathAndParentPaths($taxonomies)
    {
        if (!$taxonomies) {
            return [];
        }

        if ($taxonomies instanceof NodeInterface) {
            $taxonomies = [$taxonomies];
        }

        $pathes = [];

        foreach ($taxonomies as $taxonomy) {
            $pathes[] = $taxonomy->getPath();
            $parent = $taxonomy->getParent();
            while ($parent && $parent->getNodeType()->isOfType($this->taxonomyService->getTaxonomyNodeType())) {
                $pathes[] = $parent->getPath();
                $parent = $parent->getParent();
            }
        }

        return array_unique($pathes);
    }

    /**
     * @param NodeInterface|NodeInterface[] $taxonomies
     * @return array
     */
    public function extractIdentifierAndParentIdentifiers($taxonomies)
    {
        if (!$taxonomies) {
            return [];
        }

        if ($taxonomies instanceof NodeInterface) {
            $taxonomies = [$taxonomies];
        }

        $identifiers = [];

        foreach ($taxonomies as $taxonomy) {
            $identifiers[] = $taxonomy->getIdentifier();
            $parent = $taxonomy->getParent();
            while ($parent && $parent->getNodeType()->isOfType($this->taxonomyService->getTaxonomyNodeType())) {
                $identifiers[] = $parent->getIdentifier();
                $parent = $parent->getParent();
            }
        }

        return array_unique($identifiers);
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
