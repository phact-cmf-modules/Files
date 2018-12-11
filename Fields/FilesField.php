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
use Phact\Form\Fields\CharField;
use Phact\Main\Phact;
use Phact\Request\HttpRequestInterface;
use Phact\Translate\Translate;

class FilesField extends CharField
{
    public $inputTemplate = 'files/fields/images_field_input.tpl';

    /**
     * Sort route
     * @var
     */
    public $sortUrl = '/admin/files/sort';

    /**
     * Upload route
     * @var
     */
    public $uploadUrl = '/admin/files/upload';

    /**
     * Delete route
     * @var
     */
    public $deleteUrl = '/admin/files/delete';

    /**
     * Limit for one upload
     * @var int
     */
    public $limit = 20;

    public $limitMessage;

    public $maxSizeMessage;

    public $notAllowedMessage;

    public $accept = '*';

    public $types = [];

    public $maxFileSize = 5242880;

    public $fileField = 'file';
    public $sortField = 'position';

    public $itemAdmin = null;

    protected $_itemAdmin = null;

    public function __construct()
    {
        /** @var Translate $translate */
        if ($translate = self::fetchComponent(Translate::class)) {
            if (!$this->limitMessage) {
                $this->limitMessage = $translate->t('Files.main', 'Sorry, you can upload up to 20 files at a time');
            }
            if (!$this->maxSizeMessage) {
                $this->maxSizeMessage = $translate->t('Files.main', 'Sorry, uploaded file size exceeded');
            }
            if (!$this->notAllowedMessage) {
                $this->notAllowedMessage = $translate->t('Files.main', 'Sorry, only specified file types can be uploaded');
            }
        }
    }

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
            'name' => $this->getName(),
            'fileField' => $this->fileField,
            'sortField' => $this->sortField
        ];
    }

    public function getFieldData($encode = true)
    {
        $commonData = $this->getCommonData();

        $url = '';
        /** @var HttpRequestInterface $request */
        if ($request = self::fetchComponent(HttpRequestInterface::class)) {
            $url = $request->getUrl();
        }

        $data = [
            'url' => $url,

            'uploadUrl' => $this->routeToUrl($this->uploadUrl),
            'sortUrl' => $this->routeToUrl($this->sortUrl),
            'deleteUrl' => $this->routeToUrl($this->deleteUrl),

            'flowData' => $commonData,
            'sortData' => $commonData,
            'deleteData' => $commonData,

            'limit' => $this->limit,
            'maxFileSize' => $this->maxFileSize,
            'accept' => $this->accept,
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
        /** @var RouterInterface $router */
        if ($router = self::fetchComponent(RouterInterface::class)) {
            if (mb_strpos($url, ':', 0, 'UTF-8') !== false) {
                $url = $router->url($url);
            }
        }
        return $url;
    }
}
