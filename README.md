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

- `TaxonomyIndexing.extractIdentifierAndParentIdentifiers()` Get an array of taxon identifiers including all parent
  taxons for the given taxon nodes

### TaxonomySearch

Find taxonomies that match the fulltext query.

```
# Build searchQuery
searchQuery = ${Search.query( Taxonomy.root() ).fulltext( searchTerm ).nodeType('Sitegeist.Taxonomy:Taxonomy')}

# Number of serach results
totalSearchResults = ${this.searchQuery.count()}

# Execute query
searchResults = ${this.searchQuery.execute()}
```

Often you want to combine the search for taxons with the document serach.
This examples will find results that match the term via fulltext or that
are referencing a taxonomies that matched the fulltext.

```
# Array with IDs of taxonomies that match the term
@context.taxonomyIds = ${this.taxonomies}
@context.taxonomyIds.@process.convertToIds = Neos.Fusion:RawCollection {
    collection = ${Search.query( Taxonomy.root() ).fulltext( searchTerm ).nodeType('Sitegeist.Taxonomy:Taxonomy').execute().toArray()}
    itemName = 'taxon'
    itemRenderer = ${q(taxon).property('_identifier')}
}

# Find documents that either match fulltext or are assigned to a taxon that matched

# Build searchQuery
searchQuery = ${Search.query(site).nodeType('Neos.Neos:Document').limit(100)}

# Append setting to get results with a minimum match of 1
searchQuery.@process.setMinimumShouldMatch = ${value.request('query.filtered.query.bool.minimum_should_match', 1)}

# Append query with fulltext string search term
searchQuery.@process.setTermCondition = ${value.request('query.filtered.query.bool.should', [{'query_string': {'query': searchTerm}}])}

# Append query with search by taxonomy (if any)
searchQuery.@process.setTaxonomyCondition = ${value.appendAtPath('query.filtered.query.bool.should', {'terms': {'taxonomyReferences':taxonomyIds}})}
searchQuery.@process.setTaxonomyCondition.@if.has = ${taxonomyIds ? true : false}

# Number of serach results
totalSearchResults = ${this.searchQuery.count()}

# Execute query
searchResults = ${this.searchQuery.execute()}
```

## Indexing of Taxonomies

The title and description field of taxonomies are added to the fulltext index.

## Contribution

We will gladly accept contributions. Please send us pull requests.

## License

See [LICENSE](./LICENSE)
