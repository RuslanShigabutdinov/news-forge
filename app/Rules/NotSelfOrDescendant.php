<?php

namespace App\Rules;

use App\Models\Rubric;
use Illuminate\Contracts\Validation\Rule;

class NotSelfOrDescendant implements Rule
{
    public function __construct(private Rubric $current) {}

    public function passes($attribute, $value): bool
    {
        // parent_id пустой — всегда ок
        if (!$value) return true;

        // Нельзя указать самого себя
        if ($value == $this->current->id) return false;

        $parent = Rubric::query()
            ->select('_lft', '_rgt')
            ->find($value);

        // Проверяем: newParent находится ВНУТРИ диапазона текущего узла?
        return !(
            $parent->_lft > $this->current->_lft &&
            $parent->_rgt < $this->current->_rgt
        );
    }

    public function message(): string
    {
        return __('validation.parent_must_not_be_descendant');
    }
}
