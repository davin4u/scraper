<?php

namespace App\Crawler\Matchers;

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
     * @return int
     * @throws \Exception
     */
    public function match(string $name): int
    {
        if (empty($this->map)) {
            $this->loadMapping();
        }

        foreach ($this->map as $entityId => $map) {
            if (is_array($map) && count($map) > 0) {
                foreach ($map as $pattern) {
                    if (
                        (strcasecmp($pattern, $name) === 0)
                        || (strcmp(mb_strtolower($pattern), mb_strtolower($name)) === 0)
                    ) {
                        return (int)$entityId;
                    }
                }
            }
        }

        // create entity if not found
        // @TODO remove or change below logic after initial scraping
        $created = $this->model::create([
            'name' => $name,
            'map' => [$name]
        ]);

        $this->loadMapping();

        return $created->id;
    }

    private function loadMapping()
    {
        $this->map = [];

        if (!$this->model) {
            throw new \Exception("Property model must be set in child matcher class.");
        }

        $entities = (new $this->model)->query()->get();

        foreach ($entities as $entity) {
            $this->map[$entity->id] = $entity->map;
        }
    }
}
