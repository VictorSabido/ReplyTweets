# Reply tweets (Twitter API) :star:

Search for the selected account and reply to its latest tweets

### Long description :blue_book:
The application answers the last tweets of the user indicated. It also translates them into another language although this is optional.
Use Twitter API with which you get account information, timeline, write tweets. These functions require a development account at [Twitter Developer](https://developer.twitter.com/) to use your keys.

## Installation :wrench:

After downloading the project, go to the root.

```bash
composer install
```

Copy config.ini.example to config.ini

```bash
cp config.ini.example config.ini
```
Fill the config.ini file with the correct data.

Create a database and run the code in the *bbdd.sql* file or:

```bash
mysql -uuser -p database_name

source bbdd.sql;
```


## Usage :hammer:
Go to *index.php* and change the **$account** variable to the name of the account with which we want to use the app / bot.
It's all, now run it

```php
php index.php
```

:clock2: Can also be used with a cron!


## Contributing :blush:
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.


## License
[MIT](https://choosealicense.com/licenses/mit/)