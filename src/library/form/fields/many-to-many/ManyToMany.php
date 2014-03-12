<?php

/**
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Form\Fields\ManyToMany;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\MessageBag;
use Orangehill\Photon\Library\Form\Core\Field;
use Orangehill\Photon\Module;

class ManyToMany extends Field
{

    protected $options = null;
    protected $module;
    protected $selected = null;
    protected $hasColumn = false;

    public function __construct(\Orangehill\Photon\Field $field)
    {
        parent::__construct($field);
        $this->module = Module::find($field->module_id);
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        $table = $this->getField()->relation_table;
        if ($this->options === null && $table) {
            $this->options = $this->fetchOptions($table);
        }

        return $this->options;
    }

    /**
     * @param $relatedModuleTableName string
     *
     * @return \Eloquent
     */
    protected function fetchOptions($relatedModuleTableName)
    {
        $model = studly_case(str_singular($relatedModuleTableName));

        return $model::get();

    }

    /**
     * @return $this
     */
    public function install()
    {
        \Artisan::call("generate:pivot", array(
                'tableOne' => $this->module->table_name,
                'tableTwo' => $this->relation_table
            )
        );

        return $this;
    }

    /**
     * @return $this
     */
    public function uninstall()
    {
        $pivot = $this->getPivotInfo();
        \Artisan::call("generate:migration", array(
                'migrationName' => 'destroy_' . $pivot['pivot_table'] . '_table',
            )
        );

        return $this;
    }

    /**
     * @return array
     * <ul>
     *               <li>main_table</li>
     *               <li>related_table</li>
     *               <li>pivot_table</li>
     *               <li>main_column</li>
     *               <li>related_column</li>
     * </ul>
     */
    protected function getPivotInfo()
    {
        return array(
            'main_table'     => $this->module->table_name,
            'related_table'  => $this->relation_table,
            'pivot_table'    => self::pivotTableName($this->relation_table, $this->module->table_name),
            'main_column'    => snake_case(str_singular($this->module->table_name)) . '_id',
            'related_column' => snake_case(str_singular($this->relation_table)) . '_id'
        );
    }

    /**
     * @param $first  Table name
     * @param $second Table name
     *
     * @return string
     */
    public static function pivotTableName($first, $second)
    {
        $tables = array(str_singular($first), str_singular($second));
        sort($tables);

        return snake_case(join('_', $tables));
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function delete($id = null, array $args = array())
    {
        $this->removeByMainId($id);

        return $this;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    protected function removeByMainId($id)
    {
        $pivotData = $this->getPivotInfo();

        return \DB::table($pivotData['pivot_table'])
            ->where($pivotData['main_column'], $id)
            ->delete();

    }

    /**
     * @return $this
     */
    public function update()
    {
        // Remove all existing entries and add new ones
        $pivotData = $this->getPivotInfo();
        $this->removeByMainId($this->row['id']);

        $insertArray = array();
        foreach ($this->getValue() ? : array() as $relatedId) {
            $insertArray[] = array(
                $pivotData['main_column']    => $this->row['id'],
                $pivotData['related_column'] => $relatedId
            );
        }
        if (!empty($insertArray)) {
            \DB::table($pivotData['pivot_table'])->insert($insertArray);
        }


        return $this;
    }

    /**
     * @return array
     */
    public function getSelected()
    {
        if ($this->selected !== null) {
            return $this->selected;
        }
        $pivotData = $this->getPivotInfo();

        $selected = \DB::table($pivotData['pivot_table'])
            ->where($pivotData['main_column'], $this->row['id'])
            ->lists($pivotData['related_column']);

        $this->selected = $selected;

        return $selected;
    }

    public function validate()
    {
        $messageBag = new MessageBag();
        $pivotInfo  = $this->getPivotInfo();
        if (!$this->relation_table || !\Schema::hasTable($this->relation_table)) {
            $messageBag->add($this->name, 'Invalid relation table on ' . $this->name);
        }

        if (\Schema::hasTable($pivotInfo['pivot_table'])) {
            $messageBag->add($this->name, "Pivot table {$pivotInfo['pivot_table']} already exists.");
        }


        return $messageBag->isEmpty() ? true : $messageBag;

    }
}
