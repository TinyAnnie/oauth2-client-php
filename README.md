Add env:

OAUTH2_CLIENT_ID=
OAUTH2_CLIENT_SECRET=
OAUTH2_CLIENT_AUTHORIZE_URL=
OAUTH2_CLIENT_ACCESS_TOKEN_URL=

npm run serve

curl --location --request GET 'http://localhost:8000/api/access-token'
