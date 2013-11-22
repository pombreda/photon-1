<?php

/**
 * Description of Text
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Form\Fields\OneToMany;

use Illuminate\Support\MessageBag;
use Orangehill\Photon\Library\Form\Core\Field;
use Orangehill\Photon\Module;

class OneToMany extends Field
{

    protected $options = null;

    /**
     * @return array
     */
    public function getOptions()
    {
        if ($this->options === null && $this->getField()->relation_table) {
            $this->options = $this->fetchOptions($this->getField()->relation_table);
        }

        return $this->options;
    }

    protected function fetchOptions($relatedModuleTableName)
    {
        $relatedModule = Module::where('table_name', $relatedModuleTableName)->first();
        $modelName     = studly_case(str_singular($relatedModule->table_name));
        $data          = $modelName::get();

        return $data;
    }

    public function validate()
    {
        $messageBag = new MessageBag();
        if (!$this->relation_table || !\Schema::hasTable($this->relation_table)) {
            $messageBag->add($this->name, 'Invalid relation table on ' . $this->name);
        }

        return $messageBag->isEmpty() ? true : $messageBag;

    }
}
