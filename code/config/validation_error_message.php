<?php
/**
 * エラーメッセージ定義
 * Definition of Error message to Validation Class.
 *
 * @see Validation.php
 */
// @item should be Alphanumeric.
$validation_error_message['@alphameric'] = '<strong>%s</strong> は半角英数で入力してください';
// @item is not Date format.
$validation_error_message['@date'] = '<strong>%s</strong> の日付では登録できません';
// @item is not Email format.
$validation_error_message['@email'] = '<strong>%s</strong> は登録できない形式です';
// @item should be half size character.
$validation_error_message['@hankaku'] = '<strong>%s</strong> は半角で入力してください';
// @item should be KATAKANA.
$validation_error_message['@katakana'] = '<strong>%s</strong> はカタカナで入力してください';
// @item should be length less than @maxlength.
$validation_error_message['@maxlength'] = '<strong>%s</strong> は <strong>%s</strong> 文字以内で入力してください';
// @item should be length greater than @maxlength.
$validation_error_message['@minlength'] = '<strong>%s</strong> は <strong>%s</strong> 文字以上で入力してください';
// @item should be Numeric.
$validation_error_message['@numeric'] = '<strong>%s</strong> は 半角数字で入力してください';
// @item should be Numeric to Integers or Decimals.
$validation_error_message['@numeric_decimalpoint'] = '<strong>%s</strong> は 半角の整数もしくは小数で入力してください';
// @item should be half size character to Numeric and Minus sign.
$validation_error_message['@numeric_minus'] = '<strong>%s</strong> は 数字、マイナス符号とも半角で入力してください';
// @item is unable character type.
$validation_error_message['@password'] = '<strong>%s</strong> は登録できない形式です';
// @item should be mixing of alphanumeric.
$validation_error_message['@password_mixed_letters'] = '<strong>%s</strong> は半角英数の混合で入力してください';
// @item is required.
$validation_error_message['@required'] = '<strong>%s</strong> をご入力ください';
// @item is not Tel format.
$validation_error_message['@tel'] = '<stron>%s</strong> は登録できない形式です';
// @item is not Time format.
$validation_error_message['@time'] = '<stron>%s</strong> の時間では登録できません';
// @item is not Zipcode format.
$validation_error_message['@zipcode'] = '<stron>%s</strong> は登録できない形式です';
