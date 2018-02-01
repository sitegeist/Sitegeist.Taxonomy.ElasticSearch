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

@todo ATTENTION PSEUDOCODE FIX THIS SOONISH
```
// the query
term = 'foo'

// taxonomy nodes
taxonomies = ${Search.query( Taxonomy.root() ).fulltext(this.term).execute()}

// documents that either match fulltext or are assigned to a taxon that matched
searchQuery = ${Search.query(site).request('query.filtered.query.bool.should', [{'query_string': {'query': this.term}}, {'terms': {'taxonomyReferences': this.taxonomies}}]).nodeType('Neos.Neos:Document')}
```

## Indexing of Taxonomies

The title and description field of taxonomies are added to the fulltext index.

## Contribution

We will gladly accept contributions. Please send us pull requests.

## License

See [LICENSE](./LICENSE)
