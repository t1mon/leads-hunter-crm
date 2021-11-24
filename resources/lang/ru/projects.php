<?php

return [
    //Общие надписи на кнопках
    'button-add' => 'Добавить',
    'button-change' => 'Изменить',
    'button-save' => 'Сохранить',
    'button-delete' => 'Удалить',
    'button-cancel' => 'Отмена',

    //Общие надписи
    'not_specified' => 'Не указано',
    'actions' => 'Действия',
    
    'project' => 'Проекты',
    'integrated' => 'Интеграция Rest Api',
    'attributes' => [
        'name' => 'Имя',
        'leads_all' => 'Лидов всего',
        'leads_today' => 'Лидов сегодня',
        'created_at' => 'Дата создания',
        'project_id' => 'Идентификатор проекта',
        'api_token' => 'Api Token'
    ],

    //Flash-сообщения
    'not_authorized' => 'У вас нет полномочий на это действие',

    //Журнал
    'journal' => [
        'date' => 'Дата',
        'client' => 'Клиент',
        'class' => 'Класс',
        'name' => 'Имя',
        'surname' => 'Фамилия',
        'patronymic' => 'Отчество',
        'phone' => 'Телефон',
        'entries' => 'Кол-во вхождений',
        'email' => 'E-mail',
        'city' => 'Город',
        'cost' => 'Сумма сделки',
        'comment' => 'Комментарий',
        'host' => 'Посадочная',
        'ip' => 'IP',
        'referrer' => 'Источник',
        'url_query_string' => 'Запрос по URL',
        'source' => 'Источник',
        'count' => 'Всего :count лид|Всего :count лида|Всего :count лидов',
        'count_unique' => 'Всего :count уникальный лид|Всего :count уникальных лида|Всего :count уникальных лидов',
        'key' => 'Ключ',
        'utm' => [
            'utm_source' => '[utm_source]',
            'utm_campaign' => '[utm_campaign]',
            'utm_medium' => '[utm_medium]',
            'utm_content' => '[utm_content]',
            'utm_term' => '[utm_term]'
        ]
    ],
    //Страница рассылок
    'notifications' => [
        //Меню вкладок
        'tab_info' => 'Информация',
        'tab_email_settings' => 'Настройки e-mail',
        'tab_telegram_settings' => 'Настройки Telegram',
        'tab_webhooks' => 'Вебхуки',
        
        //Таблица с информацией о синхронизации
        'info' => [
            'info' => 'Информация о синхронизации',
            'service' => 'Служба',
            'status' => 'Статус',
            'additional' => 'Дополнительные настройки'
        ],
        
        //Таблица настроек рассылки по email
        'emails_toggle' => 'Включить рассылку по E-mail',
        'emails_settings' => 'Общие настройки',
        'emails_subject' => 'Тема письма',
        'emails_send_all' => 'Присылать все лиды',
        'emails_list' => 'Список адресов e-mail',
        'emails_fields' => 'Дополнительные поля в рассылке',
        'emails_save' => 'Сохранить',
        'emails_form_placeholder' => 'Введите e-mail',
        'emails_add' => 'Добавить адрес e-mail',
        'emails_email' => 'Адрес',
        'emails_action' => 'Действие',
        'emails_none' => 'В данный момент в базе нет адресов',

        //Таблица настроек рассылки по Telegram
        'telegram' => [
            'general_settings' => 'Общие настройки',

            'toggle' => 'Включить рассылку по Telegram',
            'fields' => 'Дополнительные поля в рассылке',

            'form_add_group' => 'Добавить групповой чат',
            'form_add_private' => 'Добавить контакт пользователя',

            'group' => 'Групповой чат',
            'private' => 'Личные сообщения',

            'username' => 'Имя пользователя',
            'user_id' => 'ID пользователя',
            'status'  => 'Статус',
            'approved' => 'Одобрен',
            'not_approved' => 'Не одобрен',

            'actions' => 'Действия',

            'create_error' => 'Ошибка при добавлении контакта',
            'error_exists' => 'Указанный контакт уже добавлен в проект',

            'group_create-success' => 'Групповой чат обновлён',
            'private_create_success' => 'Контакт добавлен в проект',

            'delete_success' => 'Контакт отвязан от проекта',

            'group_none' => 'Групповой чат не указан',
            'private_none' => 'В данный момент в базе нет контактов',
        ],

        //Заголовки таблицы
        'date' => 'Дата',
        'email_notification' => 'E-mail',
        'telegram_notification' => 'Telegram',

        //Вебхуки
        'webhooks' => [
            'add' => 'Добавить вебхук',
            'name' => 'Название вебхука',
            'placeholder' => 'Введите URL с параметрами',
            'method' => 'Метод',
            'url' => 'URL',
            'fields' => 'Отправляемые поля',

            'none' => 'В проекте не назначено вебхуков',
            'error-create' => 'Ошибка создания вебхука',
            'error-exists' => 'Вебхук с таким именем уже существует',
            'create-success' => 'Вебхук создан',
            'update-success' => 'Вебхук обновлён',
            'delete-success' => 'Вебхук удалён',
        ],

        //Прочее
        'none_available' => 'В данный момент уведомления отсутствуют',
    ],

    //Страница "Свойства проекта" (раздел Настройки -> Основные)
    'properties' => [
        'tab' => 'Свойства проекта',
        'name' => 'Название проекта',
        'timezone' => 'Часовой пояс',
    ],

    //Страница "Классы" (раздел Настройки -> Основные)
    'classes' => [
        'tab' => 'Классы',
        'create-new' => 'Создать новый класс',
        'edit' => 'Редактировать класс',
        'name' => 'Название класса',
        'color' => 'Цвет (шестнадцатеричное число формата RRGGBBAA)',
        'table' => [
            'name' => 'Название',
            'color' => 'Цвет',
            'none' => 'Пока что у проекта нет классов',
        ],
    ],

    //Страница настроек хостов
    'hosts' => [
        'host' => 'Хост',
        'action' => 'Действие',
        'project' => 'Проект',
        'add-host' => 'Добавить хост',
        'add-host-placeholder' => 'Введите адрес хоста',
        'list' => 'Список хостов',
        'delete-success' => 'Хост удалён',
        'create-error' => 'Ошибка добавления хоста',
        'create-success' => 'Хост добавлен в проект',
        'error-exists' => 'Хост уже есть в базе данных',
    ],

    //Страница настроек пользователей
    'users' => [
        //Форма добавления пользователя в проект
        'add-form' => [
            'placeholder' => 'Введите e-mail пользователя',
            'title' => 'Добавить пользователя в проект',
            'make_admin' => 'Сделать администратором',
            'view_fields' => 'Видимые поля журнала',
        ],

        //Flash-сообщения
        'delete-success' => 'Пользователь удалён из проекта',
        'create-error' => 'Ошибка добавления пользователя',
        'update-success' => 'Полномочия пользователя обновлены',
        'create-success' => 'Пользователь добавлен в проект',
        'error-exists' => 'Пользователь уже добавлен в проект',
        'error-doesnt-exist' => 'Пользователь отсутствует в базе данных',

        //Всплывающие подсказки
        'hints' => [
            'make_admin' => 'Сделать администратором',
            'view_fields' => 'Поля, которые будут отображаться для пользователя',
        ],

        //Таблица
        'table' => [
            'none' => 'На проект не назначено пользователей',
            'user' => 'Пользователь',
            'creator' => 'Создатель',
        ]
        
    ],

    //Боковая панель
    'sidebar' => [
        'journal' => 'Журнал',
        'hosts' => 'Хосты',
        'users' => 'Настройки доступа',
        'forwarding' => 'Рассылки',
        'integrations' => 'Интеграция'
    ],
    'count' => ':count проект|:count проекта|:count проектов',
    'empty_api_token' => 'Токен не установлен'
];