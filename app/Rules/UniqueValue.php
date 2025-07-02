<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueValue implements ValidationRule
{
    protected $table;
    protected $column;
    protected $connection;
    protected $ignoreId;

    public function __construct($table, $column, $connection, $ignoreId = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->connection = $connection;
        $this->ignoreId = $ignoreId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = DB::connection($this->connection)->table($this->table)->where($this->column, $value);

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        if ($query->count() > 0) {
            $fail('El :attribute ya está en uso.');
        }
    }
}