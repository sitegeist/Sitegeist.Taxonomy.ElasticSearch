# Sitegeist.Taxonomy.ElasticSearch

> elastic search integration for Sitegeist.Taxonomy

This package configures the elastic search indexing of taxonomy nodes and provides an EEL helper for custom indexing
configurations. It also provides an EEL helper for taxonomy-aware elastic search querying.

## Status

**This is currently experimental code so do not rely on any part of this.**

### Authors & Sponsors

* Martin Ficzel - ficzel@sitegeist.de

*The development and the public-releases of this package is generously sponsored by our employer http://www.sitegeist.de.*

## EEL-Helpers

### TaxonomyIndexing

- `TaxonomyIndexing.extractPathAndParentPaths()` Get an array of taxon paths including all parent taxons for the given
  taxon nodes
- `TaxonomyIndexing.extractIdentifierAndParentIdentifiers()` Get an array of taxon identifiers including all parent
  taxons for the given taxon nodes

### TaxonomySearch

This is an extended version of the search helper from `Flowpack.ElasticSearch.ContentRepositoryAdaptor` that takes
taxonomies into account.

- `TaxonomySearch.taxonomyProperty()` Define the property that contains the references to the taxonomies default
  is 'taxonomyReferences'
- `TaxonomySearch.taxonomyStartingPoint()` Define the path inside taxonomies to search like '/animals/mammals',
  by default all taxonomies are used.
- `TaxonomySearch.fulltext()` Do a fulltext-query to the taxonomies and afterwards query for fulltext matches or
  relations to the previously found taxonomies.

## Indexing of Taxonomies

The title and description field of taxonomies are added to the fulltext index.

## Contribution

We will gladly accept contributions. Please send us pull requests.

## License

See [LICENSE](./LICENSE)
