# LaravelCleanFileCache

[![Software License][ico-license]](LICENSE.md)

When using file as cache driver, Laravel creates cache files but never purges expired ones. 
This can lead to server overload of cache files which drained up storage spaces.

This package creates an artisan command `cache:cleanup` that will clean up your cache files, removing any that had expired.
You may run this manually or include it in a schedule.

Thanks to [TerrePorter](http://laravel.io/user/TerrePorter) for his suggestion on [laravel.io](http://laravel.io/forum/01-28-2016-cache-file-garbage-collection)!

Thanks to [jdavidbakr](https://github.com/jdavidbakr/laravel-cache-garbage-collector) for his packagist but I decided to learn how to create my own packagist and how this formed!

## Install

Via Composer

``` bash
$ composer require momokang/laravel-clean-file-cache
```

Then add the service provider to `app/Console/Kernel.php` in the $commands array:

``` php
\momokang\CleanFileCache\CleanFileCache::class
```

## Usage

``` bash
$ php artisan cache:cleanup
```

``` bash
$ php artisan cache:cleanup 48
```

where 48 is known as 48 hours, you can change the integer to suit your needs

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email kang1233@hotmail.com or raise an issue.

## Credits

- [momokang](https://github.com/momokang/)
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[link-contributors]: ../../contributors
