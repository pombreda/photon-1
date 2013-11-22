<?php

namespace Orangehill\Photon\Library;


use Illuminate\Database\Eloquent\Model;
use Orangehill\Photon\Library\Form\Core\FieldCollection;
use Orangehill\Photon\Library\Form\Core\FieldFactory;
use Orangehill\Photon\Module;

class ModuleManager
{
    /** @var  Module */
    protected $module;
    protected $modelName;
    protected $entry;

    public function __construct(Module $module)
    {
        $this->module    = $module;
        $this->modelName = '\\' . studly_case(str_singular($this->module->table_name));
    }

    public function updateEntry($id, $data)
    {
        $fieldCollection = $this->prepareFieldCollection($data);
        $updatables      = $this->prepareUpdatables($fieldCollection, $data);

        $model = call_user_func("{$this->modelName}::find", $id);
        $model->update($updatables);
        $fieldCollection->setRow($model->toArray())->update();

        return $model->toArray();
    }

    protected function prepareFieldCollection(array $data = array())
    {
        $fieldCollection = new FieldCollection();
        foreach ($this->module->fields as $mField) {
            $field = FieldFactory::make($mField);
            $field->setValue($data[$mField->column_name] ? : null);
            $fieldCollection->add($field);
        }

        return $fieldCollection;
    }

    protected function prepareUpdatables(FieldCollection $fieldCollection, array $data = array())
    {
        $updatables = array();
        foreach ($fieldCollection as $field) {
            if (array_key_exists($field->column_name, $data) && $field->getHasColumn()) {
                $updatables[$field->column_name] = $field->parse($data[$field->column_name]);
            }
        }

        return array_filter($updatables, function ($e) {
                return $e !== false;
            }
        );
    }

    public function createEntry(Module $module, array $data = array())
    {
        $fieldCollection = $this->prepareFieldCollection($data);
        $updatables      = $this->prepareUpdatables($fieldCollection, $data);

        /**
         * @var $model Model
         */
        $model = new $this->modelName($updatables);
        $model->save();
        $fieldCollection->setRow($model->toArray())->update();

        return call_user_func("{$this->modelName}::find", $model->id)->toArray();
    }

    public function deleteEntry($id)
    {
        $fieldCollection = $this->prepareFieldCollection();
        $fieldCollection->delete($id);

        return \DB::table($this->module->table_name)->where('id', $id)->delete();

    }

}