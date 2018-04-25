<?php

declare(strict_types=1);

/*
 * Nested Forms Bundle for Contao Open Source CMS
 *
 * @copyright  Moritz Vondano
 * @license    MIT
 * @link       https://github.com/m-vo/contao-nested-forms
 *
 */

namespace Mvo\ContaoNestedForms\Form;

use Contao\FormFieldModel;
use Contao\Widget;

class SubForm extends Widget
{
    public function generate()
    {
        throw new \BadMethodCallException();
    }

    /**
     * @param null $arrAttributes
     *
     * @return string
     */
    public function parse($arrAttributes = null): string
    {
        $fieldModels = FormFieldModel::findPublishedByPid($this->mvo_nested_forms_srcForm ?? -1);
        if (!$fieldModels) {
            return '';
        }

        // parse sub form's fields
        $output = '';
        foreach ($fieldModels as $fieldModel)
        {
            $class = $GLOBALS['TL_FFL'][$fieldModel->type];
            if (!class_exists($class)) {
                continue;
            }

            /** @var Widget $widget */
            $widget = new $class($fieldModel->row());
            $output .= $widget->parse($arrAttributes);
        }

        return $output;
    }
}