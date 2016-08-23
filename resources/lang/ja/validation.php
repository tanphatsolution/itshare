<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute を承認しましょう。',
    'active_url' => ':attribute は無効のURLです。',
    'after' => ':attribute は:date　より後である必要があります。',
    'alpha' => ':attribute は文字しか記入することが出来ません',
    'alpha_dash' => ':attribute は文字、数字、ダッシュマークのみを記入することが出来ます。',
    'alpha_num' => ':attribute は文字または数字のみを記入することが出来ます。',
    'array' => ':attribute は配列である必要があります。',
    'before' => ':attribute　は:date　より前である必要があります。',
    'between' => [
        'numeric' => ':attribute は:min から:max　の間である必要があります。',
        'file' => ':attribute は:min から:max キロバイトの間である必要があります。',
        'string' => ':attribute は:min から:max 文字の間である必要があります。',
        'array' => ':attribute は:min から:max 個の間である必要があります。',
    ],
    'boolean' => ':attribute はtrue、またはfalseである必要があります。',
    'confirmed' => ':attribute の認証が一致しませんでした。',
    'date' => ':attribute は正しい日付ではありませんでした。',
    'date_format' => ':attribute は:format　と一致しませんでした。',
    'different' => ':attribute と:other は別である必要があります。',
    'digits' => ':attribute は:digits digitsである必要があります。',
    'digits_between' => ':attribute は:min から:max digitsの間である必要があります。',
    'email' => ':attribute は正しいメールアドレスである必要があります。',
    'exists' => '選択された:attribute が正しくありません。',
    'image' => ':attribute は画像である必要があります。',
    'in' => '選択された:attribute が正しくありません。',
    'integer' => ':attribute はintegerである必要があります。',
    'ip' => ':attribute は正しいIPアドレスである必要があります。',
    'max' => [
        'numeric' => ':attribute は:max　より小さくしてください。',
        'file' => ':attribute は:max キロバイトより小さくしてください。',
        'string' => ':attribute は:max 文字以下にしてください。',
        'array' => ':attribute は:max 個以下にしてください。',
    ],
    'mimes' => ':attribute は以下のファイルタイプである必要があります。: :values.',
    'min' => [
        'numeric' => ':attribute は:min　以上である必要があります。',
        'file' => ':attribute は最低:min キロバイトより大きくしてください。',
        'string' => ':attribute は最低でも:min 字以上が必要です。',
        'array' => ':attribute は最低:min 個以上必要です。',
    ],
    'not_in' => '選択された:attribute は正しくありません。',
    'numeric' => ':attribute は数字のみを記入することが出来ます。',
    'regex' => ':attribute フォーマットが正しくありません。',
    'required' => ':attribute は必須です。',
    'required_if' => ':other が:value　の場合は、:attribute が必須です。',
    'required_with' => ':values が現在の場合、:attribute　が必須です。',
    'required_with_all' => ':values が現在の場合、:attribute　が必須です。',
    'required_without' => ':values が現在ではない場合、:attribute　が必須です。',
    'required_without_all' => ':values が現在ではない場合、:attribute　が必須です。',
    'same' => ':attribute と:other は一致している必要があります。',
    'size' => [
        'numeric' => ':attribute は:size　である必要があります。',
        'file' => ':attribute は:size キロバイトである必要があります。',
        'string' => ':attribute は:size 文字である必要があります。',
        'array' => ':attribute は:size 個を含んでいる必要があります。',
    ],
    'unique' => ':attribute はすでに使用されています。',
    'url' => ':attribute が正しくありません。',
    'timezone' => ':attribute は正しいゾーンが必要です。',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention 'attribute.rule' to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of 'email'. This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
