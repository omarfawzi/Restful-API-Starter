[![Testing](https://github.com/omarfawzi/Restful-API-Design-Starter/actions/workflows/tests.yml/badge.svg)](https://github.com/omarfawzi/Restful-API-Design-Starter/actions/workflows/tests.yml)

# Restful API Starter

Sometimes it's a hassle to come up with some good api design for your project therefore i designed this project, the project encourages the best practices of how your code modules and services should look like in order to make them less error prune, more consistent, less coupled and highly testable.


Please keep in mind that this is not the best design you can get but it's just a starter probably you will need to modify your template a little bit before integration it with real time business.

Powered By
- PHP 8.1.4
- MYSQL 8
- Laravel 9
- Open API (3.0.2)
- [OpenAPI PSR-7 Message (HTTP Request/Response) Validator](https://github.com/thephpleague/openapi-psr7-validator)
- Docker

No need for custom code schema validation, the validation is done based on the definition added on the open api schema for each operation, all you need to care about is only your business logic and ofcourse the validation of that logic only. 

# Specifications 

Added under `storage/app/schema` ([Live](https://omarfawzi.github.io/Restful-API-Starter/)).

# Get Ready
- `make rebuild` to rebuild and start services.
- Install [npm](https://nodejs.org/en/download) for local development.
- Add your new endpoints (under `app/Modules`) and open api specifications (under `storage/app/schema`).
- `make bundle` to combine and validate your specs.
- `make swagger-ui` to host your open api specs locally [localhost](http://localhost:8081).
- `make test` to start running tests.

Have a look over [Makefile](https://github.com/omarfawzi/Restful-API-Template/blob/main/Makefile) for more options.

# Request Life Cycle

![Chart.drawio.png](https://github.com/omarfawzi/Restful-API-Design-Starter/blob/main/Chart.png)

# Contributions

Feel free to create Pull Requests.
