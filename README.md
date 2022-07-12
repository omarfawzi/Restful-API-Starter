# Restful API Starter
[![Testing](https://github.com/omarfawzi/Restful-API-Starter/actions/workflows/main.yml/badge.svg)](https://github.com/omarfawzi/Restful-API-Starter/actions/workflows/main.yml)

### Requirements
- PHP >= 8.0
- MYSQL 8
- Laravel 9

### Powered By
- Open API (3.0.2)
- [OpenAPI PSR-7 Message (HTTP Request/Response) Validator](https://github.com/thephpleague/openapi-psr7-validator)

No need for custom code schema validation, the validation is done based on the specifcation of the open api schema for each operation, all you need to care about is only your business logic and ofcourse the validation of that logic as well. 

### Specifications 

Added under `storage/app/schema` ([Live](https://omarfawzi.github.io/Restful-API-Starter/)).

### Get Ready
- `make rebuild` to rebuild and start services.
- Install [npm](https://nodejs.org/en/download) for local development.
- Add your open api specifications (under `storage/app/schema`) and **don't** forget to define an `operationId` for your new operation.
- Add the new `operationId` and the corresponding `RequestHandler` to the `ApiHandler::MAP` (located at `app/Modules/Api/ApiHandler.php`). 
- `make bundle` to combine and validate your specs.
- `make swagger-ui` to host your open api specs locally [localhost](http://localhost:8081).
- `make test` to start running tests.

Have a look over [Makefile](https://github.com/omarfawzi/Restful-API-Starter/blob/main/Makefile) for more options.

### Request Life Cycle

![Chart.drawio.png](https://github.com/omarfawzi/Restful-API-Starter/blob/main/Chart.png)

### Contributions

Feel free to create Pull Requests.
