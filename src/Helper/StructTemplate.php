<?php

namespace Lack\Subscription\Helper;

class StructTemplate
{


    public function __construct(private array $data)
    {
    }


    public function parse($input) {
        if (is_array($input)) {
            return array_map([$this, "parse"], $input);
        }

        // In preg space character is
        if (preg_match("/\{\{\s*([a-zA-Z0-9_.]+)\s*:\s*([a-zA-Z0-9_]+)(\s*=\s*(.*?))?\s*\}\}/", $input, $matches)) {
            $key = trim($matches[1]);
            $type = trim($matches[2]);
            $default = $matches[4] ?? null;

            $value = $this->data[$key] ?? $default;

            switch ($type) {
                case "boolean":
                case "bool":
                    print_r ($value);
                    if ($value === "true")
                        return true;
                    if ($value === "false")
                        return false;
                    return boolval($value);

                case "int":
                    return intval($value);

                case "string":
                    return $value;
                default:
                    throw new \InvalidArgumentException("Unknown type '$type'");
            }
        }
        return $input;
    }

}