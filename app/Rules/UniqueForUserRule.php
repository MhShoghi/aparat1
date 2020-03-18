<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueForUserRule implements Rule
{
    /**
     * @var string
     */
    private $tableName;
    /**
     * @var string|null
     */
    private $columnName;
    /**
     * @var string
     */
    private $userId;
    /**
     * @var string
     */
    private $userIdField;
    private $value;

    /**
     * Create a new rule instance.
     *
     * @param string $tableName
     * @param string|null $columnName
     * @param string $userId
     * @param string $userIdField
     */
    public function __construct(string $tableName , string $columnName = null ,string $userId = null,$userIdField = 'user_id')
    {
        //
        $this->tableName = $tableName;
        $this->columnName = $columnName;
        $this->userId = $userId ?? auth()->id();
        $this->userIdField = $userIdField;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->value = $value;
        $field = empty($this->columnName) ? $attribute : $this->columnName;
        $count = DB::table($this->tableName)->where($field,$value)
            ->where($this->userIdField,$this->userId)->count();
        return $count === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->value.' already exists!' ;
    }
}
