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
 * @date 26/04/17 10:13
 */

namespace Modules\Files\Fields;


use Phact\Orm\Fields\HasManyField;

class HasManyFilesField extends HasManyField
{
    public $editable = true;

    public $formFieldConfig = [];

    public function getFormField()
    {
        return $this->setUpFormField(array_merge([
            'class' => FilesField::class
        ], $this->formFieldConfig));
    }
}