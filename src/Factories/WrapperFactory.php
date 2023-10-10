<?php

namespace Laravel\Cache\Factories;

use eftec\bladeone\BladeOne;
use ReflectionClass;

class WrapperFactory
{
    private BladeOne $blade;

    public function __construct()
    {
        $this->blade = new BladeOne(__DIR__ . '/../resources/templates', __DIR__ . '/../cache');
    }

    public function create(ReflectionClass $reflection, string $attribute_name, $original)
    {
        $template = $this->template($reflection, $attribute_name);
        return $this->instantiate($template, $reflection, $attribute_name, $original);
    }

    private function template(ReflectionClass $reflection, string $attribute_name)
    {
        return $this->blade->run('cacheable', [
            'attribute_name' => str_replace('\\', '', $attribute_name),
            'original' => (object) [
                'namespace' => $reflection->getNamespaceName(),
                'name' => $reflection->getShortName(),
                'full_qualifier' => $reflection->getName(),
            ],
            'methods' => $this->toMethod($reflection, $attribute_name),
        ]);
    }

    private function toMethod(ReflectionClass $reflection, string $attribute_name)
    {
        $method_templates = [];
        $methods = $reflection->getMethods();
        foreach ($methods as $method) {
            $attributes = $method->getAttributes($attribute_name);
            if (!empty($attributes)) {
                $method_templates[] = (object) [
                    'name' => $method->getShortName(),
                    'args' => implode(',', array_map(fn ($p) => '$' . $p->getName(), $method->getParameters())),
                    'attribute' => current($attributes),
                ];
            }
        }
        return $method_templates;
    }

    private function instantiate(string $template, ReflectionClass $reflection, string $attribute_name, $original)
    {
        eval($template);
        $attribute = str_replace('\\', '', $attribute_name);
        $wrapper_class = "{$reflection->getName()}{$attribute}AttributeWrapper";
        return new $wrapper_class($original);
    }
}