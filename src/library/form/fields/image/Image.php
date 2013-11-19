<?php

/**
 * Description of Text
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Form\Fields\Image;

use Illuminate\Support\Facades\Config;
use Orangehill\Photon\Library\Form\Core\Field;
use Orangehill\Photon\Module;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Image extends Field
{
    protected $allowedTypes = array('image/jpeg', 'image/png', 'image/gif', 'image/bmp');
    protected $relativeFolderPath;
    protected $storageFolderPath;
    protected $hash;
    protected $uploadedFile;
    protected $temporaryName;

    public function __construct(\Orangehill\Photon\Field $field)
    {
        parent::__construct($field);

        $this->module             = Module::find($field->module_id);
        $this->relativeFolderPath = Config::get('photon::photon.media_folder')
            . '/images/'
            . $this->module->table_name
            . '/'
            . snake_case($this->name);
        $this->storageFolderPath  = public_path($this->relativeFolderPath);
    }

    public function getHtmlValue()
    {
        return filter_var($this->getValue(), FILTER_VALIDATE_URL)
            ? : public_path($this->getValue());
    }

    public function uninstall()
    {
        $this->removeDirectory($this->storageFolderPath);
    }

    protected function removeDirectory($dir)
    {
        $it    = new \RecursiveDirectoryIterator($dir);
        $files = new \RecursiveIteratorIterator($it,
            \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                continue;
            }
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($dir);
    }

    public function parse($input = null)
    {
        if ($input instanceof UploadedFile && in_array($input->getMimeType(), $this->allowedTypes)) {
            $this->uploadedFile  = $input;
            $this->temporaryName = $imageName = $this->getHash() . "::{$input->getClientOriginalName()}";
            $input->move($this->storageFolderPath, $imageName);

            return $this->relativeFolderPath . '/' . $imageName;
        } else {
            return false;
        }
    }

    protected function getHash()
    {
        return $this->hash ? : ($this->hash = str_replace(array('.', ' '), '', microtime()));
    }

    public function update()
    {
        if (!($this->uploadedFile instanceof UploadedFile)) {
            return $this;
        }

        $this->delete($this->row['id']);

        $fullPath        = $this->storageFolderPath . '/' . $this->temporaryName;
        $newName         = str_replace("{$this->hash}::", $this->row['id'] . '_', $this->temporaryName);
        $newPath         = $this->storageFolderPath . '/' . $newName;
        $newRelativePath = $this->relativeFolderPath . '/' . $newName;

        rename($fullPath, $newPath);

        \DB::table($this->module->table_name)->where('id', $this->row['id'])->update(array(
                $this->column_name => $newRelativePath
            )
        );

        return $this;
    }

    public function delete($id = null)
    {
        $files = glob("{$this->storageFolderPath}/{$id}_*");
        foreach ($files as $file) {
            @unlink($file);
        }

        return $this;
    }
}