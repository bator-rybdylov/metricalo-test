### Install project via Composer
composer install

### Make purchase using API endpoint
Send a POST request to "/app/example/{payment_gateway}". Possible values for {payment_gateway} are "aci" and "shift4".

The request should contain keys: amount, currency, card_number, card_exp_month, card_exp_year and card_cvv.

### Make purchase using command
bin/console app:example {payment_gateway} 7.12 EUR 4200000000000000 08 2034 123

Possible values for {payment_gateway} are "aci" and "shift4".
