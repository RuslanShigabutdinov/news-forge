<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Rubric;
use App\Rules\NotSelfOrDescendant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_rule_fails_when_setting_self_as_parent()
    {
        $rubric = Rubric::create(['name' => 'Root']);

        $rule = new NotSelfOrDescendant($rubric);

        $validator = Validator::make(
            ['parent_id' => $rubric->id],
            ['parent_id' => [$rule]]
        );

        $this->assertTrue($validator->fails());
    }

    public function test_rule_fails_when_setting_descendant_as_parent()
    {
        $root = Rubric::create(['name' => 'Root']);
        $child = Rubric::create(['name' => 'Child']);
        $child->appendToNode($root)->save();

        $rule = new NotSelfOrDescendant($root);

        $validator = Validator::make(
            ['parent_id' => $child->id],
            ['parent_id' => [$rule]]
        );

        $this->assertTrue($validator->fails());
    }

    public function test_rule_passes_with_valid_parent()
    {
        $root = Rubric::create(['name' => 'Root']);
        $other = Rubric::create(['name' => 'Other']);

        $rule = new NotSelfOrDescendant($root);

        $validator = Validator::make(
            ['parent_id' => $other->id],
            ['parent_id' => [$rule]]
        );

        $this->assertFalse($validator->fails());
    }

    public function test_rule_passes_with_null_parent()
    {
        $rubric = Rubric::create(['name' => 'Root']);

        $rule = new NotSelfOrDescendant($rubric);

        $validator = Validator::make(
            ['parent_id' => null],
            ['parent_id' => [$rule]]
        );

        $this->assertFalse($validator->fails());
    }

}
