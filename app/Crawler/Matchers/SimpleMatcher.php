<?php

namespace App\Crawler\Matchers;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SimpleMatcher
 * @package App\Crawler\Matchers
 */
class SimpleMatcher
{
    /**
     * @var array
     */
    protected $map;

    /**
     * @param string $name
     * @param array $props
     * @param bool $returnModel
     * @return int|Model
     * @throws \Exception
     */
    public function match(string $name, array $props = [], $returnModel = false)
    {
        if (empty($this->map)) {
            $this->loadMapping();
        }

        foreach ($this->map as $entity) {
            if (is_array($entity->map) && count($entity->map) > 0) {
                foreach ($entity->map as $pattern) {
                    if (
                        (strcasecmp($pattern, $name) === 0)
                        || (strcmp(mb_strtolower($pattern), mb_strtolower($name)) === 0)
                    ) {
                        $match = true;

                        if (!empty($props)) {
                            foreach ($props as $propName => $propValue) {
                                if ($entity->{$propName} !== $propValue) {
                                    $match = false;

                                    break;
                                }
                            }
                        }

                        if ($match) {
                            return $returnModel ? $entity : (int)$entity->id;
                        }
                    }
                }
            }
        }

        // create entity if not found
        // @TODO remove or change below logic after initial scraping

        $created = $this->model::create($this->getCreateData($name, $props));

        $this->loadMapping();

        return $created->id;
    }

    /**
     * @param string $name
     * @param array $props
     * @return array
     */
    protected function getCreateData(string $name, array $props = []): array
    {
        return [
            'name' => $name,
            'map' => [$name]
        ];
    }

    private function loadMapping()
    {
        $this->map = [];

        if (!$this->model) {
            throw new \Exception("Property model must be set in child matcher class.");
        }

        $this->map = (new $this->model)->query()->get();
    }
}
