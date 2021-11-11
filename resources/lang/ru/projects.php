<?php

return [
    //Общие надписи на кнопках
    'button-add' => 'Добавить',
    'button-change' => 'Изменить',
    'button-save' => 'Сохранить',
    'button-delete' => 'Удалить',
    'button-cancel' => 'Отмена',
    
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
        'name' => 'Имя',
        'surname' => 'Фамилия',
        'patronymic' => 'Отчество',
        'phone' => 'Телефон',
        'entries' => 'Кол-во вхождений',
        'email' => 'E-mail',
        'city' => 'Город',
        'cost' => 'Сумма сделки',
        'host' => 'Посадочная',
        'source' => 'Источник',
        'count' => 'Всего :count лид|Всего :count лида|Всего :count лидов',
        'count_unique' => 'Всего :count уникальный лид|Всего :count уникальных лида|Всего :count уникальных лидов',
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
        'tab_forward_log' => 'Журнал рассылок',
        'tab_email_settings' => 'Настройки e-mail',
        'tab_telegram_settings' => 'Настройки Telegram',
        
        //Таблица настроек рассылки по email
        'emails_toggle' => 'Включить рассылку по E-mail',
        'emails_settings' => 'Настройки',
        'emails_fields' => 'Дополнительные поля в рассылке',
        'emails_save' => 'Сохранить',
        'emails_form_placeholder' => 'Введите e-mail',
        'emails_button_add' => 'Добавить',
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

        //Прочее
        'none_available' => 'В данный момент уведомления отсутствуют',
    ],

    'hosts' => [
        'host' => 'Хост',
        'action' => 'Действие',
        'project' => 'Проект',
        'add-form' => 'Добавить хост',
        'add-button' => 'Добавить',
        'delete-success' => 'Хост удалён',
        'create-error' => 'Ошибка добавления хоста',
        'create-success' => 'Хост добавлен в проект',
        'error-exists' => 'Хост уже есть в базе данных',
    ],

    //Страница настроек пользователей
    'users' => [
        //Форма добавления пользователя в проект
        'add-form' => [
            'button' => 'Добавить пользователя в проект',
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
        'users' => 'Пользователи',
        'forwarding' => 'Рассылки',
        'integrations' => 'Интеграция'
    ],
    'count' => ':count проект|:count проекта|:count проектов',
    'empty_api_token' => 'Токен не установлен'
];