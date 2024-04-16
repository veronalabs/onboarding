<?php 

namespace Veronalabs\Onboarding\Included\Fields;

use Veronalabs\Onboarding\Contracts\FieldContract;
use Veronalabs\Onboarding\Source\SourceAbstract;

class TextField extends SourceAbstract implements FieldContract
{

    public function __construct($default = [])
    {
        $this->data = $default;

        $this->allowed = [
            "name",
            "label",
            "placeholder",
            "default",
            "classes",
            "id",
            "rules"
        ];
    }

    public function validation($key)
    {
        return array_key_exists($key, $this->allowed);
    }


    public function render()
    {
        echo "<input id='{$this->data["id"]}' type='text' name='{$this->data["name"]}' value='{$this->data["default"]}' placeholder='{$this->data["placeholder"]}' />";

    }

}