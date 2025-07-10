### Clone the project and install it via Composer
composer install

### Make a purchase using the API endpoint
Send a POST request to "/app/example/{payment_gateway}". Possible values for {payment_gateway} are "aci" and "shift4".

The request should contain keys: amount, currency, card_number, card_exp_month, card_exp_year and card_cvv.

### or
### make a purchase using the CLI command
bin/console app:example {payment_gateway} 7.12 EUR 4200000000000000 08 2034 123

Possible values for {payment_gateway} are "aci" and "shift4".

### To run the tests, run the command
bin/phpunit
