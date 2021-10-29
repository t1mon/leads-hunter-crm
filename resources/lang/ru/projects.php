<?php

return [
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
    'notifications' => [
        //Меню вкладок
        'tab_forward_log' => 'Журнал рассылок',
        'tab_email_settings' => 'Настройки e-mail',
        
        //Таблица настроек рассылки по email
        'emails_toggle' => 'Включить рассылку',
        'emails_settings' => 'Настройки',
        'emails_fields' => 'Дополнительные поля в рассылке',
        'emails_save' => 'Сохранить',
        'emails_form_placeholder' => 'Введите e-mail',
        'emails_button_add' => 'Добавить',
        'emails_email' => 'Адрес',
        'emails_action' => 'Действие',
        'emails_none' => 'В данный момент в базе нет адресов',

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
    'sidebar' => [
        'journal' => 'Журнал',
        'hosts' => 'Управление хостами',
        'forwarding' => 'Рассылки',
        'integrations' => 'Интеграция'
    ],
    'count' => ':count проект|:count проекта|:count проектов',
    'empty_api_token' => 'Токен не установлен'
];
