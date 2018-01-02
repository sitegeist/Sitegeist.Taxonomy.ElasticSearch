# Sitegeist.Taxonomy.ElasticSearch
### ElasticSearch Integration for the Sitegeist.Taxonomies 

This package defines the indexing of Taxonomies via ElasticSearch and brings helpers for that and for searching with respect
of taxonomies. 

### Authors & Sponsors

* Martin Ficzel - ficzel@sitegeist.de

*The development and the public-releases of this package is generously sponsored by our employer http://www.sitegeist.de.*

## Status

**This is currently experimental code so do not rely on any part of this.**

## EEL-Helpers

### TaxonomyIndexing

- `TaxonomyIndexing.extractPathAndParentPaths()` Get an array of taxons-pathes including all parent-taxons for the given taxons-nodes.
- `TaxonomyIndexing.extractIdentifierAndParentIdentifiers()` Get an array of taxons-identifiers including ass all parent-taxons for the given taxons-nodes..

### TaxonomySearch 

This is an extended version of the search-helper that takes taxonomies into account.

- `TaxonomySearch.taxonomyProperty()` Define the property that contains the references to the taxonomies default is 'taxonomyReferences'
- `TaxonomySearch.taxonomyStartingPoint()` Define the path inside taxonomies to search like '/animals/mammals', by default all taxonomies are used. 
- `TaxonomySearch.fulltext()` Do a fulltext-query to the taxonomies and afterwards query for fulltext-matches or relations to the previously found taxonomies. 

## Indexing of Taxonomies

The title and description field of taxonomies are added to the fulltext index. 

## Contribution

We will gladly accept contributions. Please send us pull requests.
