{{--Поля--}}
@php
    $fields = [
        'ADDRESS', 'ADDRESS_2', 'ADDRESS_CITY',
        'ADDRESS_COUNTRY',
        'ADDRESS_COUNTRY_CODE',
        'ADDRESS_POSTAL_CODE',
        'ADDRESS_PROVINCE',
        'ADDRESS_REGION',
        'ASSIGNED_BY_ID',
        'BIRTHDATE',
        'COMMENTS',
        'COMPANY_ID',
        'COMPANY_TITLE',
        'CONTACT_ID',
        'CREATED_BY_ID',
        'CURRENCY_ID',
        'DATE_CLOSED',
        'DATE_CREATE',
        'DATE_MODIFY',
        'EMAIL',
        'HAS_EMAIL',
        'HAS_PHONE',
        'HONORIFIC',
        'ID',
        'IM',
        'IS_RETURN_CUSTOMER',
        'LAST_NAME',
        'MODIFY_BY_ID',
        'NAME',
        'OPENED',
        'OPPORTUNITY',
        'ORIGINATOR_ID',
        'ORIGIN_ID',
        'ORIGIN_VERSION',
        'PHONE',
        'POST',
        'SECOND_NAME',
        'SOURCE_DESCRIPTION',
        'SOURCE_ID',
        'STATUS_DESCRIPTION',
        'STATUS_ID',
        'STATUS_SEMANTIC_ID',
        'TITLE',
        'UTM_CAMPAIGN',
        'UTM_CONTENT',
        'UTM_MEDIUM',
        'UTM_SOURCE',
        'UTM_TERM',
        'WEB',
        ];
@endphp

{{--Форма для добавления вебхука--}}
@include('material-dashboard.project.webhooks._form', ['type' => \App\Models\Project\Project::WEBHOOK_BITRIX24,])

{{--Список доступных вебхуков--}}
@include('material-dashboard.project.webhooks._list', ['type' => \App\Models\Project\Project::WEBHOOK_BITRIX24,])