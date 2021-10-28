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
        'email' => 'Email',
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
        //Меню редактирования адресов
        'notifications_enabled' => 'Уведомления включены',
        'notifications_disabled' => 'Уведомления отключены',
        'email_collapse' => 'Адреса e-mail',
        'telegram_collapse' => 'Адреса Telegram',
        
        //Таблица email-адресов
        'emails_header' => 'СПИСОК АДРЕСОВ EMAIL',
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
        'notification' => 'Уведомления',
        'integrations' => 'Интеграция'
    ],
    'count' => ':count проект|:count проекта|:count проектов',
    'empty_api_token' => 'Токен не установлен'
];
