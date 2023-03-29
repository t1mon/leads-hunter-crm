# Laravel 8.0 L.Corp

[![Build Status](https://travis-ci.org/guillaumebriday/laravel-blog.svg?branch=master)](https://travis-ci.org/guillaumebriday/laravel-blog)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/guillaumebriday)

The purpose of this repository is to show good development practices on [Laravel](http://laravel.com/) as well as to present cases of use of the framework's features like:

- [Authentication](https://laravel.com/docs/8.x/authentication)
- API
  - Token authentication
  - [API Resources](https://laravel.com/docs/8.x/eloquent-resources)
  - Versioning
- [Blade](https://laravel.com/docs/8.x/blade)
- [Broadcasting](https://laravel.com/docs/8.x/broadcasting)
- [Cache](https://laravel.com/docs/8.x/cache)
- [Email Verification](https://laravel.com/docs/8.x/verification)
- [Filesystem](https://laravel.com/docs/8.x/filesystem)
- [Helpers](https://laravel.com/docs/8.x/helpers)
- [Horizon](https://laravel.com/docs/8.x/horizon)
- [Localization](https://laravel.com/docs/8.x/localization)
- [Mail](https://laravel.com/docs/8.x/mail)
- [Migrations](https://laravel.com/docs/8.x/migrations)
- [Policies](https://laravel.com/docs/8.x/authorization)
- [Providers](https://laravel.com/docs/8.x/providers)
- [Requests](https://laravel.com/docs/8.x/validation#form-request-validation)
- [Seeding & Factories](https://laravel.com/docs/8.x/seeding)
- [Testing](https://laravel.com/docs/8.x/testing)
- [Homestead](https://laravel.com/docs/8.x/homestead)

Beside Laravel, this project uses other tools like:

- [Bootstrap 4](https://getbootstrap.com/)
- [PHP-CS-Fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer)
- [Travis CI](https://travis-ci.org/)
- [Font Awesome](http://fontawesome.io/)
- [Vue.js](https://vuejs.org/)
- [axios](https://github.com/mzabriskie/axios)
- [Redis](https://redis.io/)
- [spatie/laravel-medialibrary](https://github.com/spatie/laravel-medialibrary)
- Many more to discover.

## Some screenshots

You can find some screenshots of the application on : [https://imgur.com/a/Jbnwj](https://imgur.com/a/Jbnwj)

## Installation

Development environment requirements :
- [VirtualBox](https://www.virtualbox.org/)
- [Vagrant](https://www.vagrantup.com/)

Setting up your development environment on your local machine :
```bash
$ git clone https://github.com/guillaumebriday/laravel-blog.git
$ cd laravel-blog
$ cp .env.example .env
$ composer install
$ vagrant up
$ vagrant ssh
```

All following commands must be run inside the VM:
```bash
$ cd code
$ yarn install
$ artisan key:generate
$ artisan horizon:install
$ artisan telescope:install
$ artisan storage:link
```

Now you can access the application via [http://localhost:8000](http://localhost:8000).

**There is no need to run `php artisan serve`. PHP is already running in the dedicated virtual machine.**

## Before starting
You need to run the migrations with the seeds :
```bash
$ artisan migrate --seed
```

This will create a new user that you can use to sign in :
```yml
email: darthvader@deathstar.ds
password: 4nak1n
```

And then, compile the assets :
```bash
$ yarn dev # or yarn watch
```

Starting job for newsletter :
```bash
$ artisan tinker
> PrepareNewsletterSubscriptionEmail::dispatch();
```

## Useful commands
Seeding the database :
```bash
$ artisan db:seed
```

Running tests :
```bash
$ ./vendor/bin/phpunit --cache-result --order-by=defects --stop-on-defect
```

Running php-cs-fixer :
```bash
$ ./vendor/bin/php-cs-fixer fix --config=.php_cs --verbose --dry-run --diff
```

Generating backup :
```bash
$ artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
$ artisan backup:run
```

Generating fake data :
```bash
$ artisan db:seed --class=DevDatabaseSeeder
```

Discover package
```bash
$ artisan package:discover
```

In development environnement, rebuild the database :
```bash
$ artisan migrate:fresh --seed
```

## Accessing the API

Clients can access to the REST API. API requests require authentication via token. You can create a new token in your user profile.

Then, you can use this token either as url parameter or in Authorization header :

```bash
# Url parameter
GET http://laravel-blog.app/api/v1/posts?api_token=your_private_token_here

# Authorization Header
curl --header "Authorization: Bearer your_private_token_here" http://laravel-blog.app/api/v1/posts
```

API are prefixed by `api` and the API version number like so `v1`.

Do not forget to set the `X-Requested-With` header to `XMLHttpRequest`. Otherwise, Laravel won't recognize the call as an AJAX request.

To list all the available routes for API :

```bash
$ artisan route:list --path=api
```

## Contributing

Do not hesitate to contribute to the project by adapting or adding features ! Bug reports or pull requests are welcome.

## License

This project is released under the [MIT](http://opensource.org/licenses/MIT) license.
# leads-hunter-crm

# Настройка интеграции с Telegram
## 1. Создание бота в Botfather
Выполнить следующие действия в чате [Botfather](https://t.me/BotFather):
1. Создать бота
2. Записать username и API-токен бота
2. Назначить ему команды **start** и **stop**
3. Вызвать команду **/setprivacy** и выбрать опцию **DISABLED**. Это позволит получать боту сообщения из чата

## 2. Подключение бота к CRM
### 2.1. Подготовка Leads Hunter CRM
1. В интеграции используется пакет **westacks/telebot**. Обновить пакеты из composer:
    ```bash
    composer install
    composer update
    ```

2. Опубликовать файл конфигурации telebot:
    ```bash
    php artisan vendor:publish --provider="WeStacks\TeleBot\Laravel\Providers\TeleBotServiceProvider" --tag=config
    ```

3. В файле **.env** добавить следующие поля и поставить в них соответствующие значения:
    ```
    TELEGRAM_ENABLED=true # Включить/выключить интеграцию с Telegram на уровне всей CRM. По умолчанию false
    TELEGRAM_API_URL= # Адрес, на который отправляются запросы бота. По умолчанию https://api.telegram.org/bot{TOKEN}/{METHOD}
    TELEGRAM_BOT_NAME= # Username бота
    TELEGRAM_BOT_TOKEN= # API-токен бота
    TELEGRAM_NGROK_WEBHOOK_URL= # Адрес вебхука на ngrok при тестировании на местном сервере
    ```

4. В файле **config/telebot.php** проставить настройки для бота:
    ```php
    'bots' => [
            'bot' => [
                'token' => env('TELEGRAM_BOT_TOKEN'),
                'name' => env('TELEGRAM_BOT_NAME', null),
                'api_url' => env('TELEGRAM_API_URL', 'https://api.telegram.org/bot{TOKEN}/{METHOD}'),
                'exceptions' => true,
                'async' => false,

                'webhook' => [
                    'url'               => env('TELEGRAM_NGROK_WEBHOOK_URL', env('APP_URL')) .'/api/v2/integrations/telegram/webhook',
                    // 'url'               => env('TELEGRAM_BOT_WEBHOOK_URL', env('APP_URL').'/telebot/webhook/bot/'.env('TELEGRAM_BOT_TOKEN')),
                    // 'certificate'       => env('TELEGRAM_BOT_CERT_PATH', storage_path('app/ssl/public.pem')),
                    // 'ip_address'        => '8.8.8.8',
                    // 'max_connections'   => 40,
                    // 'allowed_updates'   => ["message", "edited_channel_post", "callback_query"],
                    // 'secret_token'      => env('TELEGRAM_KEY', null),
                ],

                'poll' => [
                    // 'limit'             => 100,
                    // 'timeout'           => 0,
                    // 'allowed_updates'   => ["message", "edited_channel_post", "callback_query"]
                ],

                'handlers' => [
                    // Your update handlers
                ],
            ],

            // 'second_bot' => [
            //     'token'         => env('TELEGRAM_BOT2_TOKEN', '123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11'),
            // ],
        ],
    ```

### 2.2. Подключение бота к вебхуку
#### 2.2.3. Подготовка на местном сервере (ngrok)
1. Запустить ngrok:
    ```console
    ngrok http 8000
    ```
2. Скопировать ссылку из поля **Forwarding** с протоколом **https** и вставить её в поле **TELEGRAM_NGROK_WEBHOOK_URL** в файле **.env**:
    ```bash
    TELEGRAM_NGROK_WEBHOOK_URL=https://05d4-46-0-239-117.ngrok.io #Пример ссылки
    ```
    
    **ВАЖНО!** Помните, что при каждом новом запуске ngrok ссылка генерируется заново! Не забывайте подставлять новую ссылку в .env и заново назначать вебхук! В противном случае интеграция не будет работать!

#### 2.2.4. Подготовка на хостинге
1. Очистить поле **TELEGRAM_NGROK_WEBHOOK_URL** в файле **.env**:
    ```bash
    TELEGRAM_NGROK_WEBHOOK_URL= #Поле должно быть пустым!
    ```
    **ВАЖНО!** Поле **TELEGRAM_NGROK_WEBHOOK_URL** обязательно должно быть пустым, иначе по умолчанию вебхук будет назначен на ссылку ngrok!
#### 2.2.5. Подключение к вебхуку
Выполнить команду:

 ```bash
 php artisan telebot:webhook --setup
 ```

Убедиться в правильности назначенного вебхука можно с помощью команды:

```bash
 php artisan telebot:webhook --info
 ```

## 3. Настройка интеграции в проекте Leads Hunter CRM
### 3.1. Создание чата в настройках синхронизации проекта
1. Перейдите в настройки синхронизации проекта
2. Выберите вкладку Telegram
3. Создайте новый чат
4. Запишите код приглашения чата

### 3.2. Подключение интеграции в группе/канале в приложении Telegram
1. В приложении Telegram создайте новую группу
2. Добавьте в группу участников и бота, подключенного к Leads Hunter CRM
3. В группе отправьте боту сообщение, в котором упоминаете бота и указываете код приглашения
  ```
  @имя_бота код_приглашения
  ```
4. Бот пришлёт сообщение, подтверждающее завершение интеграции.

### 3.3. Подключение интеграции в личном чате в приложении Telegram
1. В приложении Telegram перейдите в чат с ботом.
2. Отправьте боту код приглашения чата, созданного в п.3.1. Упоминаний в личном чате, в сообщении должен быть только код.
3. Бот пришлёт сообщение, подтверждающее завершение интеграции.

## 4. Управление интеграцией
### 4.1. Форматирование сообщения
Бот отправляет сообщения в чаты по указанному шаблону. Для каждого чата используется свой индивидуальный шаблон.

Шаблон представляет собой обычный текст, где с помощью ключевых слов указываются, куда подставлять данные лида:

  ```
  У вас новый лид!
  Имя: $full_name
  Телефон: $phone
  Город: $city
  Цена сделки: $cost
  ```

Ключевое слово помечается символом **$**. Доступны следующие ключевые слова:
| Ключевое слово     | Значение    |
|--------------------|-------------|
| **name**           | Имя         |
| **patronymic**     | Отчество    |
| **surname**        | Фамилия     |
| **full_name**      | ФИО         |
| **cost**           | Цена сделки |
| **city**           | Город       |
| **region**         | Регион лида, определённый автоматически |
| **manual_region**  | Регион лида, указанный менеджером вручную |
| **email**          | Адрес электронной почты |
| **host**           | Посадочная |

### 4.2. Включение и отключение уведомлений
#### 4.2.1. В группе/канале
1. Включить уведомления:
   ```bash
   /start@Имя_бота
   ```
2. Выключить уведомления:
   ```bash
   /stop@Имя_бота
   ```
#### 4.2.2 В личном чате
1. Включить уведомления:
   ```bash
   /start
   ```
2. Выключить уведомления:
   ```bash
   /stop
   ```