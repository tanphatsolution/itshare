<?php

return [
    'accepted' => 'Mục :attribute cần được chấp nhận.',
    'active_url' => 'Mục :attribute không được là một đường dẫn vô hiệu.',
    'after' => 'Mục :attribute phải là một ngày sau :date.',
    'alpha' => 'Mục :attribute chỉ được phép là chữ cái.',
    'alpha_dash' => 'Mục :attribute chỉ được phép bao gồm chữ cái, chữ số và dấu phẩy.',
    'alpha_num' => 'Mục :attribute chỉ được phép bao gồm chữ cái và chữ số.',
    'array' => 'Mục :attribute must be an array.',
    'before' => 'Mục :attribute phải là một ngày trước :date.',
    'between' => [
        'numeric' => ':attribute phải có giá trị trong khoảng từ :min đến :max.',
        'file' => ':attribute phải có dung lượng trong khoảng từ :min đến :max kilobytes.',
        'string' => ':attribute phải có từ :min đến :max kí tự.',
        'array' => ':attribute phải có từ :min đến :max mục.',
    ],
    'boolean' => ':attribute phải có giá trị có hoặc không.',
    'confirmed' => ':attribute đang nhập sai.',
    'date' => 'Mục :attribute đang là một ngày không tồn tại.',
    'date_format' => 'Mục :attribute không hợp lệ so với mẫu :format.',
    'different' => ':attribute phải khác :other.',
    'digits' => ':attribute phải có số chữ số là :digits.',
    'digits_between' => ':attribute phải có số chữ số từ :min đến :max.',
    'email' => ':attribute không được phép là địa chỉ vô hiệu.',
    'exists' => 'Mục đang được chọn :attribute vô hiệu.',
    'image' => ':attribute phải là ảnh.',
    'in' => 'Mục :attribute đang được chọn vô hiệu.',
    'integer' => ':attribute phải là số nguyên dương.',
    'ip' => ':attribute phải là một địa chỉ IP có hiệu lực.',
    'max' => [
        'numeric' => ':attribute không được phép lớn hơn :max.',
        'file' => ':attribute không được có dung lượng vượt quá :max kilobytes.',
        'string' => ':attribute không được phép có quá :max kí tự.',
        'array' => ':attribute không được phép có nhiều hơn :max mục.',
    ],
    'mimes' => ':attribute phải là một file thuộc kiểu :values.',
    'min' => [
        'numeric' => ':attribute phải có ít nhất :min.',
        'file' => ':attribute phải có dung lượng lớn hơn :min kilobytes.',
        'string' => ':attribute phải có ít nhất :min kí tự.',
        'array' => ':attribute phải có ít nhất :min mục.',
    ],
    'not_in' => 'Mục :attribute vô hiệu.',
    'numeric' => ':attribute phải là số.',
    'regex' => ':attribute có mãu không hợp lệ.',
    'required' => ':attribute cần phải nhập.',
    'required_if' => ':attribute cần phải nhập khi giá trị của :other là :value.',
    'required_with' => ':attribute cần phải nhập khi giá trị của :values là hiện tại.',
    'required_with_all' => ':attribute cần phải nhập khi giá trị của :values là hiện tại.',
    'required_without' => ':attribute cần phải nhập khi giá trị của :values không phải là hiện tại.',
    'required_without_all' => ':attribute cần phải nhập khi không giá trị nào của :values là hiện tại.',
    'same' => ':attribute và :other phải giống nhau.',
    'size' => [
        'numeric' => 'Kích cỡ của :attribute phải là :size.',
        'file' => 'Dung lượng của :attribute phải là :size kilobytes.',
        'string' => ':attribute phải có :size kí tự.',
        'array' => ':attribute phải bao gồm :size mục.',
    ],
    'unique' => ':attribute đã từng được nhập rồi.',
    'url' => 'Mục :attribute format vô hiệu.',
    'timezone' => ':attribute phải là một múi giờ có tồn tại.',
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
