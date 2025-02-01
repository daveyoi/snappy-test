## Snappy PHP Code Task

## Setup
Standard Laravel Installation

```bash
composer install
```

configure a standard .env with a mysql Database
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=snappy
DB_USERNAME=root
DB_PASSWORD
```

```php
php artisan migrate
```

## Console Command
```php
php artisan app:import-postcodes
```
This command could be scheduled to run overnight.

### Design Considerations

The bulk of the logic is abstracted to a PostcodeService which is
called from the command. This allows for reuse of the same code if required and better
opportunities to test each part of the download, extract and populate process.

The package [Laravel Excel](https://docs.laravel-excel.com/3.1/getting-started/) has been used
to ease the import process via the Imports/PostcodesImport class.

### Performance
The command sets the memory capacity to 512mb via an ini_set function this should
be extracted to the php.ini file in a real production environment.

The PostcodesImport class also allows for chunking and batch inserts to keep memory usage
under control when dealing with larger files.

### Further Development
More error handling for the file download and extraction process.

## Endpoints

### Add a shop
```php
POST /api/shop
Payload:
[
    'name' => 'required',
    'latitude' => 'required',
    'longitude' => 'required',
    'status' => 'required',
    'type' => 'required',
    'max_delivery_distance' => 'required',
]
```

Some basic validation ensures the required fields are passed to the endpoint. With more time
I would further enhance this validation to include data types required and ensure duplication was
protected against before the database for performance.

This endpoint currently expects the frontend or the calling service to already know the
latitude and longitude - a further enhancement would be to accept an address and geocode
the coordinates using a geocoding api.

### Get Nearest/Can Deliver Shops
```PHP
GET /api/nearest/{postcode}
GET /api/deliver/{postcode}
```

The endpoints are expecting a well-formed postcode with a space between the outcode and incode,
I would add some extra validation to ensure the postcode was in the expected format before
processing.

Both endpoints have their own controller with an index method which utilises the abstracted ShopService
to share logic between requests.

### Further Development
With more time I would add model scopes to the model look-ups to reduce code duplication or preferably
abstract all database interactions to a repository pattern. The benefit of the repository pattern include
centralisation of database calls and enhanced testing via interfaces.

## Testing
I am using Pest and Feature tests for this project. Feature testing requires a valid mysql connection
due to the geocoding distance functions used in MySQL.


## Setup
The test suite is already configured to use the applications mysql connection. A testing schema will need to be
created prior to testing.

## Running Tests

To run the whole test suite.
```PHP
php artisan test
```

This includes an expensive external call to test the postcode download functionality.

To run just the shop endpoints
```PHP
php artisan test --group=shop
```

## Further Improvements
Rather than using Bounding Boxes in PHP you could use MySQL geospatial columns, indexes and 
functions to run the same queries. In my limited research there is the opportunity for performance
increase, but you could also add further index improvements to the current queries.

## Security
All endpoints are un protected in this project,  in a real project they would all be protected
behind a login/auth flow and the post would use CSRF to ensure the request was coming from an authorised
origin.

