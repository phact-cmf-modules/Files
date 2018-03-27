<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @company HashStudio
 * @site http://hashstudio.ru
 * @date 14/11/16 12:52
 */

namespace Modules\Files\Fields;

use Modules\Files\Validators\RequiredFilesValidator;
use Phact\Form\Fields\FileField;
use Phact\Main\Phact;

class UploadFileField extends FileField
{
    public $inputTemplate = 'files/fields/upload_file_field_input.tpl';

    /**
     * Upload route
     * @var
     */
    public $uploadUrl = 'files:large_upload';

    /**
     * Delete route
     * @var
     */
    public $deleteUrl = 'files:large_delete';

    /**
     * Limit for one upload
     * @var int
     */
    public $limit = 20;

    public $limitMessage = 'Извините, загрузить можно только 1 файл';

    public $maxSizeMessage = 'Извините, превышен размер загружаемого файла';

    public $notAllowedMessage = 'Извините, можно загрузить только указанные типы файлов';

    public $accept = ['*/*'];

    public $types = [];

    public $maxFileSize = 104857600; // 100 Mb


    public function setDefaultValidators()
    {
        if ($this->required) {
            $validator = new RequiredFilesValidator($this->requiredMessage);
            $validator->field = $this->getName();
            $validator->owner = $this->getForm()->getInstance();
            $this->_validators[] = $validator;
        }
    }

    public function getRenderValue()
    {
        $instance = $this->getForm()->instance;
        if (!$instance->pk) {
            return null;
        } else {
            return $instance->{$this->getName()};
        }
    }

    public function getModelClass()
    {
        $instance = $this->getForm()->instance;
        $field = $instance->{$this->getName()};
        $model = $field->getModel();
        return $model->className();
    }

    public function getItemAdmin()
    {
        if (!$this->_itemAdmin && $this->itemAdmin) {
            $this->_itemAdmin = new $this->itemAdmin;
        }
        return $this->_itemAdmin;
    }

    public function getCommonData()
    {
        $instance = $this->getForm()->instance;
        return [
            'pk' => $instance->pk,
            'class' => $instance->className(),
            'name' => $this->getName()
        ];
    }

    public function getFieldData($encode = true)
    {
        $commonData = $this->getCommonData();
        $data = [
            'url' => Phact::app()->request->getUrl(),

            'sortUrl' => null,
            'uploadUrl' => $this->routeToUrl($this->uploadUrl),
            'deleteUrl' => $this->routeToUrl($this->deleteUrl),

            'flowData' => $commonData,
            'sortData' => [],
            'deleteData' => $commonData,

            'limit' => $this->limit,
            'maxFileSize' => $this->maxFileSize,
            'accept' => $this->getHtmlAccept(),
            'types' => $this->types,


            'limitMessage' => $this->limitMessage,
            'maxSizeMessage' => $this->maxSizeMessage,
            'notAllowedMessage' => $this->notAllowedMessage,
        ];

        if ($encode) {
            return json_encode($data);
        }
        return $data;
    }

    public function routeToUrl($url)
    {
        if (mb_strpos($url, ':', 0, 'UTF-8') !== false) {
            $url = Phact::app()->router->url($url);
        }
        return $url;
    }
}