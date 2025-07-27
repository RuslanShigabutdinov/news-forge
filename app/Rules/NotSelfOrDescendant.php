<?php

namespace App\Rules;

use App\Models\Rubric;
use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class NotSelfOrDescendant implements ValidationRule
{
    public function __construct(private readonly Rubric $current) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 1) parent_id не передан — пропускаем
        if (!$value) {
            return;
        }

        // 2) Нельзя назначить родителем саму себя
        if ($value == $this->current->id) {
            $fail(__('validation.parent_must_not_be_descendant'));
            return;
        }

        // 3) Проверяем, не является ли выбранный parent дочерним узлом
        $parent = Rubric::query()->select('_lft', '_rgt')->find($value);

        if (
            $parent &&
            $parent->_lft > $this->current->_lft &&
            $parent->_rgt < $this->current->_rgt
        ) {
            $fail(__('validation.parent_must_not_be_descendant'));
        }
    }

}
