# Symfony Util

Symfony util provides common utilies and extensions that promote certain styles of developing symfony apps.

## Installation

Install with composer at `krak/symfony-util`.


## Usage

### DependencyInjection

#### createLoader

Creates a fully featured delegating loader similar to the default loader created in the root Kernel class. This is useful simplifying the configuration and imports for Apps with multiple bundles.

#### registerTaggedServiceLocator

Performs the boiler plate of registering a service locator of a specific for a specific service.

#### createServiceReferenceMapFromTaggedIds

Creates a reference map to be used for a ServiceLocator from a set of taggedIds and an optional keyAttribute to search the tags and use as the key. If no keyAttribute is provided or if no tags contain that key, then it just defaults to use the id as the reference map keys.

#### pickLastAttributeFromTags

When finding registered tags, symfony will return an array of tags per id. There are times when you just want to pick out one attribute from the tags, but you need to do it from the last defined tag. This method handles that for you.
