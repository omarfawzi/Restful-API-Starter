[![Testing](https://github.com/${{ env.REPOSITORY_FULL_NAME }}/actions/workflows/main.yml/badge.svg)](https://github.com/${{ env.REPOSITORY_FULL_NAME }}/actions/workflows/main.yml)

# Restful API Starter

**Avoid** request schema code validation, starter which is integrated with the agnostic open api schema + validation in order to provide the best restful design for your API services, care about your business logic validation, implementation and leave the starter to take care of the rest.  

### Request Life Cycle

![Chart.drawio.png](https://github.com/${{ env.REPOSITORY_FULL_NAME }}/blob/main/Chart.png)

### Requirements
- PHP >= 8.0
- MYSQL 8
- Laravel 9

### Powered By
- Open API (3.0.2)
- [OpenAPI PSR-7 Message (HTTP Request/Response) Validator](https://github.com/thephpleague/openapi-psr7-validator)

### Specifications 

Added under `storage/app/schema` ([Live](https://${{ env.REPOSITORY_OWNER }}.github.io/${{ env.REPOSITORY_SLUG }}/)).

### Routes

Routes are imported from the open api specifications to provide a more generic way for configuring routes, 
this also means that accessing any routes not specified in the open api specifications would return an error.

For more info check `App\Providers\RouteServiceProvider::configureRoutes`

### Security

Security is done based on the specification as well and handled by the `App\Modules\OpenApi\Services\AuthenticationManager`

### Get Ready
- `make rebuild` to rebuild and start services.
- Install [npm](https://nodejs.org/en/download) for local development.
- Add your open api specifications (under `storage/app/schema`) and **don't** forget to define an `operationId` for your new operation.
- Add the new `operationId` and the corresponding `RequestHandler` to the `ApiHandler::MAP` (located at `app/Modules/Api/ApiHandler.php`). 
- `make bundle` to combine and validate your specs.
- `make swagger-ui` to host your open api specs locally [localhost](http://localhost:8081).
- `make test` to start running tests.

Have a look over [Makefile](https://github.com/${{ env.REPOSITORY_FULL_NAME }}/blob/main/Makefile) for more options.

### Contributions

Feel free to create Pull Requests.
