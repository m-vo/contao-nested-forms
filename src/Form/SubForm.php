<?php

declare(strict_types=1);

/*
 * Nested forms bundle for Contao Open Source CMS
 *
 * @copyright  Copyright (c) $date, Moritz Vondano
 * @license MIT
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
     */
    public function parse($arrAttributes = null): string
    {
        $fieldModels = FormFieldModel::findPublishedByPid($this->mvo_nested_forms_srcForm ?? -1);
        if (!$fieldModels) {
            return '';
        }

        // parse sub form's fields
        $output = '';
        foreach ($fieldModels as $fieldModel) {
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
