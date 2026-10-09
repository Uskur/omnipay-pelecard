# Changelog

All notable changes to `uskur/omnipay-pelecard` will be documented in this file.

## 2.0.0 - 2026-10-09

### Added
- Added regression coverage for iframe purchases, currencies, test credentials,
  status requests, and transaction-reference parsing.
- Documented production, test-mode, status, and callback usage.

### Deprecated
- Nothing

### Changed
- Raised the minimum runtime to PHP 8.1 and migrated from Omnipay 2 to
  Omnipay 3. This is the breaking compatibility boundary from the 1.x series.

### Fixed
- Restored `purchase()` support for the iframe gateway.
- Replaced obsolete hard-coded test credentials with configurable parameters.
- Restored support for the ISO `ILS` currency code.
- Preserved ISO currency values while Omnipay calculates payment amounts.
- Restored transaction references returned in Pelecard result data.
- Preserved request transaction references when failed status responses omit them.
- Added support for Pelecard callback transaction-reference fields.
- Restored the per-payment `QAResultStatus` parameter for test accounts.
- Returned gateway error messages safely when initialization fails.
- Removed the hard-coded successful QA result from status requests.

### Removed
- Nothing

### Security
- Nothing
