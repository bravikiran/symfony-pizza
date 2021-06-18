#create or update db
set -ex

php app/console doctrine:migrations:execute

exec "$@"
