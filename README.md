[![Testing](https://github.com/omarfawzi/Restful-API-Design-Starter/actions/workflows/tests.yml/badge.svg)](https://github.com/omarfawzi/Restful-API-Design-Starter/actions/workflows/tests.yml)

# Restful API Starter

Sometimes it's a hassle to come up with some good api design for your project therefore i designed this project, the project encourages the best practices of how your code modules and services should look like in order to make them less error prune, more consistent, less coupled and highly testable.

Please keep in mind that this is not the best design you can get but it's just a starter probably you will need to modify your template a little bit before integration it with real time business.

Powered By
- PHP 8.1.4
- MYSQL 8
- Laravel 9
- Open API (3.0.2) + [OpenAPI PSR-7 Message (HTTP Request/Response) Validator](https://github.com/thephpleague/openapi-psr7-validator)
- Docker

# Specifications 

Added under `storage/app/schema/openapi.yaml` ([Live](https://omarfawzi.github.io/Restful-API-Starter/)).

# Get Ready
- `make rebuild` to rebuild and start services.
- `make swagger-ui` to see your open api specs over [localhost](http://localhost:8081).
- `make test` to start running tests.

Have a look over [Makefile](https://github.com/omarfawzi/Restful-API-Template/blob/main/Makefile) for more options.

# Improvements

When your project specifications grows bigger having one yaml file to describe your specifications would become a pain in the **s therefore you can split the specifications into multiple yaml files, create a main yaml file, reference the other files in your specifications, build, validation and combine all of them into one file using a package like [Swagger Cli](https://www.npmjs.com/package/swagger-cli).

# Request Life Cycle

![Chart.drawio.png](https://github.com/omarfawzi/Restful-API-Design-Starter/blob/main/Chart.png)

# Contributions

Feel free to create Pull Requests.
