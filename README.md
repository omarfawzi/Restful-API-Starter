# Restful API Template

API Template for clean restful services

Built on top of
- PHP 8.1.4
- MYSQL 8
- Laravel 9
- Open API (3.0.2) + [OpenAPI PSR-7 Message (HTTP Request/Response) Validator](https://github.com/thephpleague/openapi-psr7-validator)
- Docker

# Commands
- `make rebuild` to rebuild and start services.
- `make swagger-ui` to see the open api specs over [localhost](http://localhost:8081).
- `make test` to start running tests.

# Specifications 

Added under `storage/app/schema/openapi.yaml` ([Specs](https://github.com/omarfawzi/Restful-API-Template/blob/main/storage/app/schema/openapi.yaml)).

# Improvements

When your project specifications grows bigger having one yaml file to describe your specifications would become a pain in the **s therefore you can split the specifications into multiple yaml files, create a main yaml file and reference the other files using a package like [Swagger Cli](https://www.npmjs.com/package/swagger-cli).

Have a look over [Makefile](https://github.com/omarfawzi/Restful-API-Template/blob/main/Makefile) for more options.

Feel free to contribute.
