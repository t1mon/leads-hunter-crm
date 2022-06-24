<?php

return [
    'enabled' => [
        'false' => 'Проект выключен'
    ],
    'access' => [
        'denied' => 'В доступе отказано.'
    ],

    //Общие надписи на кнопках
    'button-add' => 'Добавить',
    'button-change' => 'Изменить',
    'button-save' => 'Сохранить',
    'button-delete' => 'Удалить',
    'button-cancel' => 'Отмена',

    //Общие надписи
    'yes' => 'Да',
    'no' => 'Нет',

    'not_specified' => 'Не указано',
    'actions' => 'Действия',
    'status-active' => 'Активен',
    'status-suspended' => 'Приостановлен',

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
        'referrer' => 'referrer',
        'url_query_string' => 'Запрос по URL',
        'source' => 'Источник',
        'count' => 'Всего :count лид|Всего :count лида|Всего :count лидов',
        'count_unique' => 'Всего :count уникальный лид|Всего :count уникальных лида|Всего :count уникальных лидов',
        'key' => 'Ключ',
        'utm_source' => '[utm_source]',
        'utm_campaign' => '[utm_campaign]',
        'utm_medium' => '[utm_medium]',
        'utm_content' => '[utm_content]',
        'utm_term' => '[utm_term]',
        'utm' => [
            'utm_source' => '[utm_source]',
            'utm_campaign' => '[utm_campaign]',
            'utm_medium' => '[utm_medium]',
            'utm_content' => '[utm_content]',
            'utm_term' => '[utm_term]'
        ]
    ],

    //Страница лога
    'log' => [
        'tabs' => [
            'leads' => 'Лиды',
            'all' => 'Все',
            'info' => 'Информационные',
            'warnings' => 'Предупреждения',
            'errors' => 'Ошибки',
        ],

        'none' => 'Записей по проекту нет',
        'show-all' => 'Показать всё',
    ],

    //Страница рассылок
    'notifications' => [
        //Меню вкладок
        'tab_info' => 'Информация',
        'tab_email_settings' => 'Настройки e-mail',
        'tab_telegram_settings' => 'Настройки Telegram',
        'tab_webhooks' => 'Вебхуки',
        'tab_bitrix24' => 'Битрикс24',

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

            'types' => [
                'type' => 'Тип',
                'common' => 'Общий',
                'bitrix24' => 'Битрикс24'
            ],

            'total-active' => 'Включенных вебхуков',

            'none' => 'В проекте не назначено вебхуков',
            'error-create' => 'Ошибка создания вебхука',
            'error-exists' => 'Вебхук с таким именем уже существует',
            'create-success' => 'Вебхук создан',
            'update-success' => 'Вебхук обновлён',
            'delete-success' => 'Вебхук удалён',

            'common' => [
                'fields' => [
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
                    'cost' => 'Сумма',
                    'comment' => 'Комментарий',
                    'host' => 'Посадочная',
                    'ip' => 'IP',
                    'referrer' => 'Реферрер',
                    'url_query_string' => 'Запрос по URL',
                    'source' => 'Источник',
                    'count' => 'Всего :count лид|Всего :count лида|Всего :count лидов',
                    'count_unique' => 'Всего :count уникальный лид|Всего :count уникальных лида|Всего :count уникальных лидов',
                    'key' => 'Ключ',
                    'utm_source' => '[utm_source]',
                    'utm_campaign' => '[utm_campaign]',
                    'utm_medium' => '[utm_medium]',
                    'utm_content' => '[utm_content]',
                    'utm_term' => '[utm_term]'
                ],
            ],
            //Битрикс24
            'bitrix24' => [
                'fields' => [
                    'ADDRESS' => 'Адрес контакта',
                    'ADDRESS_2' => 'Вторая страница адреса',
                    'ADDRESS_CITY' => 'Город',
                    'ADDRESS_COUNTRY' => 'Страна',
                    'ADDRESS_COUNTRY_CODE' => 'Код страны',
                    'ADDRESS_POSTAL_CODE' => 'Почтовый индекс',
                    'ADDRESS_PROVINCE' => 'Область',
                    'ADDRESS_REGION' => 'Район',
                    'ASSIGNED_BY_ID' => 'Связано с пользователем по ID',
                    'BIRTHDATE' => 'Дата рождения',
                    'COMMENTS' => 'Комментарии',
                    'COMPANY_ID' => 'Привязка лида к компании',
                    'COMPANY_TITLE' => 'Название компании, привязанной к лиду',
                    'CONTACT_ID' => 'Привязка лида к контакту',
                    'CREATED_BY_ID' => 'Кем создана',
                    'CURRENCY_ID' => 'Идентификатор валюты',
                    'DATE_CLOSED' => 'Дата закрытия',
                    'DATE_CREATE' => 'Дата создания',
                    'DATE_MODIFY' => 'Дата изменения',
                    'EMAIL' => 'Адрес электронной почты',
                    'HAS_EMAIL' => 'Проверка заполненности поля электронной почты',
                    'HAS_PHONE' => 'Проверка заполненности поля телефон',
                    'HONORIFIC' => 'Вид обращения',
                    'ID' => 'Идентификатор контакта ',
                    'IM' => 'Мессенджеры',
                    'IS_RETURN_CUSTOMER' => 'Признак повторного лида',
                    'LAST_NAME' => 'Фамилия',
                    'MODIFY_BY_ID' => 'Идентификатор автора последнего изменения',
                    'NAME' => 'Имя',
                    'OPENED' => 'Доступен для всех',
                    'OPPORTUNITY' => 'Предполагаемая сумма',
                    'ORIGINATOR_ID' => 'Идентификатор источника данных',
                    'ORIGIN_ID' => 'Идентификатор элемента в источнике данных',
                    'ORIGIN_VERSION' => 'Оригинальная версия',
                    'PHONE' => 'Телефон контакта',
                    'POST' => 'Должность',
                    'SECOND_NAME' => 'Отчество',
                    'SOURCE_DESCRIPTION' => 'Описание источника',
                    'SOURCE_ID' => 'Идентификатор источника',
                    'STATUS_DESCRIPTION' => 'Описание статуса',
                    'STATUS_ID' => 'Идентификатор статуса',
                    'STATUS_SEMANTIC_ID' => 'Словесный идентификатор статуса',
                    'TITLE' => 'Название лида',
                    'UTM_CAMPAIGN' => 'Обозначение рекламной кампании',
                    'UTM_CONTENT' => 'Содержание кампании',
                    'UTM_MEDIUM' => 'Тип трафика',
                    'UTM_SOURCE' => 'Рекламная система',
                    'UTM_TERM' => 'Условие поиска кампании',
                    'WEB' => 'URL ресурсов лида',
                ],
            ],
        ],
        //Прочее
        'none_available' => 'В данный момент уведомления отсутствуют',
    ],

    //Страница "Свойства проекта" (раздел Настройки -> Основные)
    'properties' => [
        'tab' => 'Свойства проекта',
        'info' => 'Информация о проекте',
        'name' => 'Название проекта',
        'description' => 'Описание проекта',
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
