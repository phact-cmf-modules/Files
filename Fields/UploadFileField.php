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
use Phact\Request\HttpRequestInterface;
use Phact\Router\RouterInterface;
use Phact\Translate\Translate;

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
    public $limit = 1;

    public $limitMessage;

    public $maxSizeMessage;

    public $notAllowedMessage;

    public $accept = ['*/*'];

    public $types = [];

    public $maxFileSize = 104857600; // 100 Mb

    public function __construct()
    {
        /** @var Translate $translate */
        if ($translate = self::fetchComponent(Translate::class)) {
            if (!$this->limitMessage) {
                $this->limitMessage = $translate->t('Files.main', 'Sorry, you can upload only 1 file');
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
            'name' => $this->getName()
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
        /** @var RouterInterface $router */
        if ($router = self::fetchComponent(RouterInterface::class)) {
            if (mb_strpos($url, ':', 0, 'UTF-8') !== false) {
                $url = $router->url($url);
            }
        }
        return $url;
    }
}