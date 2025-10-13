# Changelog

All notable changes to `sdk-client` will be documented in this file.

## 2.1.0 - 2025-10-13

Added `getRelativeUri()` function

## 2.0.1 - 2025-10-13

Remove out-of-band version management

## 2.0.0-beta.3 - 2025-10-13

Update exception handling

## 2.0.0-beta.2 - 2025-10-13

Extract out `PendingRequest` logic

## 1.14.0 - 2025-02-03

Allow carbon 3.x

## 1.13.0 - 2025-01-30

Revert back to non-static initialization methods

## 1.12.0 - 2025-01-30

Refactor Guzzle initialization logic into static functions

## 1.11.2 - 2024-11-18

Fixed casting boolean json values to multipart form data

## 1.11.1 - 2024-09-20

Format multipart data as strings using serialize()

## 1.11.0 - 2024-09-20

Added function to convert json arrays into multipart request bodies

## 1.10.3 - 2024-09-10

Allow null as the return type for JSON functions

## 1.10.2 - 2024-08-05

Fix return types for multipart requests

## 1.10.1 - 2024-08-05

Fix composer version

## 1.10.0 - 2024-08-05

Add support for making multipart/form-data POST requests

## 1.9.0 - 2024-03-26

Add postJsonAsDto

## 1.8.0 - 2024-03-26

Add version info to composer.json

## 1.7.0 - 2024-03-26

Add support for customizing some Guzzle options

- User agent
- Accept
- Cookies

## 1.6.0 - 2024-03-04

- Added PATCH support
- Testing improvements

## 1.5.1 - 2024-02-01

Minor improvements

## 1.5.0 - 2023-08-11

Add ability to define request-specific headers

## 1.4.0 - 2023-08-11

Added default casters for date types

## 1.3.0 - 2023-08-11

Added support for nesbot/carbon dates

## 1.2.0 - 2023-08-09

Added a predefined caster for date/datetimes

## 1.1.0 - 2023-08-09

Added ability to load an array of DTOs via a GET request

## 1.0.1 - 2023-08-08

Make it easier to apply custom logic to guzzle initialization

## 1.0.0 - 2023-08-08

Initial release
