<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Traits;

use JsonException;
use ReflectionClass;
use ReflectionProperty;
use Sushi\Sushi;
use Thunk\Verbs\Models\VerbSnapshot;

trait GetsRowsFromVerbsStates
{
    use Sushi;

    public function getRows(): array
    {
        $stateClass = $this->stateClass;

        return VerbSnapshot::where('type', $stateClass)
            ->whereNotNull('last_event_id')
            ->get()->map(/**
             * @throws JsonException
             */ function ($row) {
                $record = json_decode((string) $row->data, true);
                // $record = (array) $stateClass::load($row->state_id);
                // These next two foreach blocks simply ensure that all records have the same
                // number of columns regardless of any changes to the state since the record was created.
                // If public properties have been added since a record was created, it gets added to the record with
                // a null value. If a public property has been removed since the record was created, it is removed when
                // hydrating it. This is an ugly hack and one of the reasons why we only use this whole setup for rapid
                // prototyping and **do not recommend using it in a production application (unless you're us).**
                foreach ($this->getSchema() as $key => $value) {
                    if (! isset($record[$key])) {
                        $record[$key] = null;
                    }
                }
                foreach ($record as $key => $value) {
                    if (! array_key_exists($key, $this->getSchema())) {
                        unset($record[$key]);
                    }
                }
                $record['id'] = $row->state_id;

                return collect($record)->map(fn ($value) => is_array($value) ? json_encode($value, JSON_THROW_ON_ERROR) : $value);
            })->toArray();
    }

    public function getSchema(): array
    {
        $reflection = new ReflectionClass($this->stateClass);

        return collect($reflection->getProperties(ReflectionProperty::IS_PUBLIC))
            ->mapWithKeys(fn (ReflectionProperty $property) => [$property->getName() => 'string'])
            ->toArray();
    }
}
