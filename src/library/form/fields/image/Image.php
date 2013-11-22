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
            . "/{$this->module->table_name}/"
            . $this->column_name;
        $this->storageFolderPath  = public_path($this->relativeFolderPath);
    }

    /**
     * Get a relative path if the value isn't a valid URL, get the full url otherwise
     *
     * @return string
     */
    public function getHtmlValue()
    {
        return filter_var($this->getValue(), FILTER_VALIDATE_URL)
            ? : public_path($this->getValue());
    }

    /**
     * Uninstall hook that triggers an image directory removal
     *
     * @return $this
     */
    public function uninstall()
    {
        $this->removeDirectory($this->storageFolderPath);

        return $this;
    }

    /**
     * Remove a directory from the system
     *
     * @param $dir string Directory path
     */
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

    /**
     * @param UploadedFile $input Uploaded Image File
     *
     * @return string|false
     */
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

    /**
     * @return Image
     */
    public function update()
    {
        if (!($this->uploadedFile instanceof UploadedFile)) {
            return $this;
        }

        $this->delete($this->row['id']);

        $fullPath        = $this->storageFolderPath . '/' . $this->temporaryName;
        $newName         = str_replace("{$this->hash}::", '', $this->temporaryName);
        $newPath         = "{$this->storageFolderPath}/{$this->row['id']}/{$newName}";
        $newRelativePath = "{$this->relativeFolderPath}/{$this->row['id']}/{$newName}";

        mkdir("{$this->storageFolderPath}/{$this->row['id']}", 0777, true);
        rename($fullPath, $newPath);

        \DB::table($this->module->table_name)
            ->where('id', $this->row['id'])
            ->update(array(
                    $this->column_name => $newRelativePath
                )
            );

        return $this;
    }

    /**
     * Removes an image directory and updates the parent row
     *
     * @param int $id Id of the row that contains this entry
     *
     * @return Image
     */
    public function delete($id = null, array $args = array())
    {
        if ($this->module instanceof Module) {
            \DB::table($this->module->table_name)
                ->where('id', $id)
                ->update(array($this->column_name => ''));
        }

        $dir = "{$this->storageFolderPath}/{$id}";
        if (is_dir($dir)) {
            $this->removeDirectory("{$this->storageFolderPath}/{$id}");
        }

        return $this;
    }
}