## 4.0.0 - 2025-10-29

### Added
- PHP 8.5 compatibility
- Support for Laravel 12.x
- Proper type declarations for ArrayAccess methods
- Improved type hints across the codebase

### Changed
- Updated PHP requirement to ^8.4|^8.5
- Modernized composer dependencies (PHPUnit ^9.0|^10.0|^11.0, Mockery ^1.4, etc.)
- Replaced deprecated `is_a()` with `instanceof` operator
- Replaced deprecated `GuzzleHttp\json_decode()` with native `json_decode()`
- Updated all comparison operators from `==` to `===` for strict type checking
- Updated phpunit.xml to modern PHPUnit 10+ format
- Fixed dynamic property declarations to prevent deprecation warnings

### Fixed
- Fixed ArrayAccess return type declarations in PageResult
- Fixed potential null pointer issues in parse_url usage
- Fixed initialization of class properties to prevent dynamic property warnings
- Improved null coalescing operator usage throughout the codebase

## 3.2.1 - 2022-01-17

- Relax guzzle version

## 3.2.0 - 2021-12-30

- Support for [Topics API](https://unsplash.com/documentation#topics)

## 3.1.0 - 2020-10-05
- Add support for guzzle 7.x dependency.
- Add optional `order_by` param to search.

## 3.0.0 - 2020-06-03

### Removed
- Remove functions calling deprecated API endpoints.
  - categories
  - anything 'curated'
  - `GET /photos/search`
- Remove top-level `Crew` namespace
- Dropped PHP <= 7.2 support

### Updated

- Updated dependencies (primarily for dev/tests).

## 2.5.1 - 2020-04-07
### Added
- Added `Accept-Encoding: gzip` to requests.

## 2.5.0 - 2019-08-14
### Added
- `toArray` method added to `ArrayObject` and `Endpoint`. https://github.com/unsplash/unsplash-php/pull/102/

## 2.4.4 - 2019-02-5
### Added
- `rateLimitRemaining` method added to `Unsplash\ArrayObject` class to fetch the remaining rate limit (thanks @KevinBatdorf)

## 2.4.3 - 2018-03-30
### Added
- Relax dependency for guzzle to include 6.3.x (https://github.com/unsplash/unsplash-php/pull/88)
- Add `__isset` method to `Endpoint` (https://github.com/unsplash/unsplash-php/pull/89)

## 2.4.2 - 2018-02-28
### Added
- Added support for `orientation` and `collections` filters to photo search.

## 2.4.1 - 2017-12-18
### Updated
- Update dependecy version (>= 1.0.3) on oauth2-unsplash

## 2.4.0 - 2017-12-11
### Removed
- Remove deprecated `category` parameters

### Updated
- Support for tracking photo downloads as per updated API guidelines

## 2.3.4 - 2017-10-06
### Updated
- Update dependecy version on oauth2-client (>= 1.4.2)

## 2.3.3 - 2017-10-04
### Updated
- Update dependecy version on oauth2-unsplash

## 2.2.1 - 2016-09-04
### Fix
- The parameters order was breaking code using older version of the package

## 2.2.0 - 2016-08-21
### Added
- Curated photos endpoint (thanks @janhenckens #40)

## 2.1.0 - 2016-08-02
### Updated
- OAuth2-Client package
- Migrate CI to travis

## 2.0.0 - 2016-04-14
#### Added
- New enpoints for user's collection

## 1.2.0 - 2016-04-05
#### Added
- New enpoints for collection

## 1.1.2 - 2016-02-23
### Added
- New badge in README to keep track of dependencies

### Updated
- New version of OAuth2-Client package (thanks @zembrowski #26)

## 1.1.1 - 2016-02-22
### Added
- New method to retrieve the number of element in collection

## 1.1.0 - 2016-02-09
### Updated
- New major version of OAuth2-Client package (thanks @zembrowski #23)

## 1.0.4 - 2015-10-20
### Added
- New endpoint to retrieve photos a user liked
- New endpoints to like and unlike photos on the behalf of the current user

## 1.0.3 - 2015-09-22
### Added
- New endpoint to retrieve a random photo (thanks @DannyWeeks #18)

## 1.0.2 - 2015-09-03
- Improve phpDoc block (thanks @freezy-sk #15)
### Fixed
- Headers were wrongly parsed when requesting total pages (thanks @freezy-sk #13)

## 1.0.1 - 2015-08-26
### Added
- Include stats endpoints

## 1.0.0 - 2015-07-30
- Launch
