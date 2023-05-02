<?php

namespace App\Traits;

use App\Enums\ArticleSectionTypes;
use App\Models\ArticleSections;
use Illuminate\Support\Collection;

trait ArticleSectionsTrait
{
    public Collection $createdSections;

    private function prepareDataFromRequest($request)
    {
        $data = [
            'name' => $request->get('name'),
            'label' => $request->get('label'),
            'label_read_only' => $request->get('label_read_only'),
            'template' => $request->get('template'),
            'type' => $request->get('type'),
            'compatibility' => $request->get('compatibility'),
            'allow_compatibility' => $request->get('allow_compatibility'),
            'schema' => $request->get('schema'),
        ];

        $complex = [];
        if ($data['type'] === ArticleSectionTypes::COMPLEX) {
            $complex = $request->get('sections');
            $data['complex_section_settings'] = $request->get('complex_section_settings') ?? null;
        }

        return [
            'simple' => $data,
            'complex' => $complex,
        ];
    }

    private function prepareDataFromObject($object, $useExist = false, $level = 0): array
    {
        try {
            $level++;
            $data = [
                'name' => $this->defineSectionName($object->name),
                'label' => $object->label,
                'label_read_only' => $object->label_read_only,
                'template' => $object->template,
                'type' => $object->type,
                'compatibility' => null,
                'schema' => $object->schema,
                'complex_section_settings' => null,
            ];

            $articleSection = null;

            if ($useExist || $level > 0) {
                $articleSection = ArticleSections::exist([
                    'name' => $object->name,
                    'label' => $object->label,
                    'label_read_only' => (int)$object->label_read_only,
                    'template' => $object->template,
                    'type' => $object->type,
                    'schema' => is_null($object->schema) ? null : json_encode($object->schema),
                    'complex_section_settings' => is_null($object->complex_section_settings) ? null : json_encode($object->complex_section_settings),
                ])->latest()->first();
            }
            if (!$articleSection) {
                $articleSection = ArticleSections::create($data);
            }


            $key = $this->createdSections->search(function ($item) use ($object, $articleSection) {
                return $item['oldId'] == $object->id && $item['oldVersion'] == $object->version;
            });

            if ($key === false) {
                $this->createdSections->add([
                    'oldId' => $object->id,
                    'oldVersion' => $object->version,
                    'newId' => $articleSection->id,
                ]);
            }

            $data['articleSection'] = $articleSection;

            $complex = [];
            if ($data['type'] === ArticleSectionTypes::COMPLEX) {
                $complex = [];
                if ($object->complex_section_settings) {
                    $data['complex_section_settings'] = $object->complex_section_settings;
                }
                foreach ($object->sections as $index => $section) {
                    $cSections = $this->prepareDataFromObject($section, $useExist, $level);
                    if ($object->complex_section_settings) {
                        $key = array_search($index, array_column(json_decode(json_encode($data['complex_section_settings']), TRUE), 'index'));
                        if ($key !== false) {
                            $data['complex_section_settings'][$key]->version_id = $cSections['simple']['articleSection']->latestVersion->id;
                            //$data['complex_section_settings'][$key]->pivot_id = $cSections['simple']['articleSection']->pivot?->id;
                        }
                    }
                    $complex[] = $cSections;
                }


                if ($object->complex_section_settings) {
                    $articleSection->complex_section_settings = $data['complex_section_settings'];
                }

                if ($compatibility = $object->compatibility) {
                    if ($compatibility->allow && $compatibility->allow->values) {
                        foreach ($compatibility->allow->values as $k => $section) {
                            if (gettype($section) !== "object") continue;
                            $key = $this->createdSections->search(function ($item) use ($section) {
                                try {
                                    return $item['oldId'] == $section->id;
                                } catch (\Exception $e) {
                                    return false;
                                }
                            });
                            if ($key === false) {
                                $cSections = $this->prepareDataFromObject($section, true, $level);
                                $compatibility->allow->values[$k] = $cSections['simple']['articleSection']->id;
                            } else {
                                $compatibility->allow->values[$k] = $this->createdSections[$key]['newId'];
                            }
                        }
                    }
                    if ($compatibility->deny && $compatibility->deny->values) {
                        foreach ($compatibility->deny->values as $k => $section) {
                            if (gettype($section) !== "object") continue;
                            $key = $this->createdSections->search(function ($item) use ($section) {
                                try {
                                    return $item['oldId'] == $section->id;
                                } catch (\Exception $e) {
                                    return false;
                                }
                            });
                            if ($key === false) {
                                $cSections = $this->prepareDataFromObject($section, false, $level);
                                $compatibility->deny->values[$k] = $cSections['simple']['articleSection']->id;
                            } else {
                                $compatibility->deny->values[$k] = $this->createdSections[$key]['newId'];
                            }
                        }
                    }

                    $articleSection->compatibility = $compatibility;

                }

                ArticleSections::withoutVersion(function () use ($articleSection) {
                    $articleSection->save();
                });
            }


            $schema = [];
            foreach ($complex as $key => $section) {
                $schema[] = [
                    'article_simple_section_id' => $section['simple']['articleSection']->id,
                    'version_id' => null,
                    'complex_section_version_id' => $articleSection->latestVersion->id,
                ];
            }


            if (sizeof($schema) > 0) {
                $articleSection->sections()->detach();
                $articleSection->sections()->sync($schema);
                if(is_array($articleSection->complex_section_settings) && sizeof($articleSection->complex_section_settings)>0){
                    $complex_section_settings = $articleSection->complex_section_settings;

                    $articleSection->sections->each(function($section, $index) use (&$complex_section_settings){
                        $key = array_search($index, array_column($complex_section_settings, 'index'));

                        if($key !== false) {
                            $complex_section_settings[$key]['pivot_id'] = $section->pivot->id;
                        }
                    });
                    $articleSection->complex_section_settings = $complex_section_settings;

                    ArticleSections::withoutVersion(function () use ($articleSection) {
                        $articleSection->save();
                    });
                }
            }

            return [
                'simple' => $data,
                'complex' => $complex,
            ];

        } catch (\Exception $e){
            dd($e->getMessage());
        }

    }

    private function defineSectionName($originalName)
    {
        $i = 0;
        $name = $originalName;
        do {

            if ($i) {
                $name = "$originalName ($i)";
            }
            $i++;

            $nameExist = ArticleSections::where('name', $name)->exists();

        } while ($nameExist);

        return $name;
    }
}
