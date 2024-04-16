<?php 

namespace Veronalabs\Onboarding\Included\Fields;

use Veronalabs\Onboarding\Contracts\FieldContract;
use Veronalabs\Onboarding\Source\SourceAbstract;

class TemplateField extends SourceAbstract implements FieldContract
{

    public function __construct($default = [])
    {
        $this->data = $default;

        $this->allowed = [
            "path",
            "label"
        ];
    }

    public function validation($key)
    {
        return array_key_exists($key, $this->allowed);
    }
    public function render()
    {
        echo $this->data['path'];
    }

}