Rendszerhez szükséges környezet:
* Docker Desktop

Telepítés a project clone-ozása után:
1. cp app/.env app/.env.local
2. docker-compose up -d --build
3. docker-compose exec php /bin/bash
4. composer install

Ha symfony keretrendszer települt, akkor a következő paranccsal lehet lefuttatni a kódot:
symfony console app:calculate-points