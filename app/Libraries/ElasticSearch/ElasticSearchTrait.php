<?php

namespace App\Libraries\ElasticSearch;

trait ElasticSearchTrait
{
    public function createIndex(string $index, array $settings = [], array $mappings = [], array $aliases = [])
    {
        try {
            $params = [
                'index' => $index,
                'body' => [
                    'settings' => (object)$settings,
                    'mappings' => (object)$mappings,
                    'aliases' => (object)$aliases
                ],
            ];

            return $this->elasticsearch->indices()->create($params);
        } catch (\Exception $e) {
            return json_decode($e->getMessage(), true);
        }
    }

    public function getIndex(string $index)
    {
        try {
            $params = [
                'index' => $index
            ];

            return $this->elasticsearch->indices()->get($params);
        } catch (\Exception $e) {
            return json_decode($e->getMessage(), true);
        }
    }

    public function deleteIndex(string $index)
    {
        try {
            $params = [
                'index' => $index
            ];

            return $this->elasticsearch->indices()->delete($params);
        } catch (\Exception $e) {
            return json_decode($e->getMessage(), true);
        }
    }

    public function createDocument(array $params)
    {
        try {
            return $this->elasticsearch->index($params);
        } catch (\Exception $e) {
            return json_decode($e->getMessage(), true);
        }
    }

    public function getDocument(array $params)
    {
        try {
            return $this->elasticsearch->get($params);
        } catch (\Exception $e) {
            return json_decode($e->getMessage(), true);
        }
    }

    public function deleteDocument(array $params)
    {
        try {
            return $this->elasticsearch->delete($params);
        } catch (\Exception $e) {
            return json_decode($e->getMessage(), true);
        }
    }
}
