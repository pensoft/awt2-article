<?php

namespace App\Transformers;

use JsonPath\JsonObject;
use League\Fractal\Serializer\DataArraySerializer;

class CustomSerializer extends DataArraySerializer
{
    /**
     * @throws \JsonPath\InvalidJsonException
     */
    public function mergeIncludes($transformedData, $includedData)
    {
        $includedData = array_map(function ($include) {
            return $include['data'];
        }, $includedData);

        $res = parent::mergeIncludes($transformedData, $includedData);

        // This conditional is triggered only if we serializing Layout responce
        if (isset($res['is_layout'])) {
            try {
                return $this->transformWithJsonPath($res);
            } catch (\Exception $error){}
        }

        return $res;
    }

    /**
     * Transform the responce with the needed settings defined for the selected Layout
     *
     * @param $data
     * @return mixed
     * @throws \JsonPath\InvalidJsonException
     */
    private function transformWithJsonPath($data)
    {
        $jsonObject = new JsonObject($data);

        // remove the parameter which we using to trigger this method, which is only for Layouts
        $this->replaceRow('$.is_layout', $jsonObject);

        $template = $jsonObject->getJsonObjects('$.template')[0];

        $schemaSettings = $jsonObject->get('$.schema_settings')[0];

        if (!$schemaSettings || $schemaSettings == '') {
            return $data;
        }
        $sections = $template->getJsonObjects('$.sections')[0];

        $settingRows = explode(PHP_EOL, $schemaSettings);

        foreach ($settingRows as $row) {
            try {
                $this->replaceRow($row, $sections);
            } catch (\Exception $error) {

            }
        }

        return json_decode($jsonObject->getJson(), JSON_OBJECT_AS_ARRAY);
    }

    /**
     * Doing the real magic! Replacing the value of the parameter or delete by defined JSONPath
     *
     * @param $row
     * @param $sections
     * @return void
     */
    private function replaceRow($row, &$sections): void
    {
        if (!$row) {
            return;
        }
        $items = explode('===', $row);
        @[$jsonPath, $value] = collect($items ?? [])->map(fn ($item) => trim($item));
        if($value) {
            $sections->set($jsonPath, $value);
        } else {
            $pathArr = explode('.', $jsonPath);
            $field = array_pop($pathArr);
            $jsonPath = implode('.', $pathArr);
            $sections->remove($jsonPath, $field);
        }
    }
}
