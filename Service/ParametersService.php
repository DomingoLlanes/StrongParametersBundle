<?php

namespace DomingoLlanes\StrongParametersBundle\Service;

use DomingoLlanes\StrongParametersBundle\Exception\UnpermittedParameterException;
use Symfony\Component\Yaml\Yaml;

class ParametersService
{
    /**
     * @var string
     */
    private $resource;

    /**
     * @var bool
     */
    private $exception;

    /**
     * @var array
     */
    private $resourceConfiguration;

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @param $resource
     * @param $controllerData
     * @return array
     */
    public function permitParameters($resource, $controllerData)
    {
        $this->resourceConfiguration = Yaml::parseFile($this->resource . $resource . ".yml");

        if (array_key_exists('deny', $this->resourceConfiguration)) {
            foreach ($this->resourceConfiguration['deny'] as $item) {
                $this->handleParameterDeny($item, $controllerData);
            }
        }

        if (array_key_exists('allow', $this->resourceConfiguration)) {
            foreach ($controllerData as $key => $item) {
                if (is_array($item)) {
                    $this->handleParameterArray($key, $controllerData);
                } else {
                    $this->handleParameterScalar($key, $controllerData);
                }
            }
        }

        return $controllerData;
    }

    private function handleParameterDeny($key, &$data)
    {
        if (isset($data[$key])) {
            $this->handleUnpermittedParameter($key, $data);
        }
    }

    private function handleParameterScalar($key, &$data)
    {
        if (!array_key_exists($key, $this->resourceConfiguration['allow']) || is_array($data[$key])) {
            $this->handleUnpermittedParameter($key, $data);
        }
    }

    private function handleParameterArray($key, &$data)
    {
        if (!array_key_exists($key, $this->resourceConfiguration['allow']) || !is_array($this->resourceConfiguration['allow'][$key])) {
            $this->handleUnpermittedParameter($key, $data);
        }

        $this->handleParameterSubarray($key, $data);
    }

    private function handleParameterSubarray($key, &$data, $resourceConfiguration = null)
    {
        if ($resourceConfiguration === null) {
            $resourceConfiguration = $this->resourceConfiguration['allow'];
        }

        foreach ($data[$key] as $k => $item) {
            if (!array_key_exists($k, $resourceConfiguration[$key])) {
                $this->handleUnpermittedParameter($k, $data[$key]);
            } else if (is_array($data[$key][$k])) {
                $this->handleParameterSubarray($k, $data[$key], $resourceConfiguration[$key]);
            }
        }
    }

    private function handleUnpermittedParameter($key, &$data)
    {
        if ($this->exception) {
            throw new UnpermittedParameterException("The parameter $key is not allowed by configuration");
        } else {
            unset($data[$key]);
        }
    }
}