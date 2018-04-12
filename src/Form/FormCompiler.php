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

use Contao\Form;
use Contao\FormFieldModel;
use Contao\System;

class FormCompiler
{
    /**
     * Insert sub forms.
     *
     * @param FormFieldModel[] $fields
     * @param string           $formFieldId
     * @param Form             $form
     *
     * @return FormFieldModel[]
     */
    public function onInject(array $fields, string $formFieldId, Form $form): array
    {
        $offset = 0;
        foreach (array_values($fields) as $index => $field) {
            if ('mvo_nested_forms_subForm' !== $field->type) {
                continue;
            }

            $subFields = $this->compileSubFormFields($field);

            // HOOK: compile sub form fields
            if (isset($GLOBALS['TL_HOOKS']['compileFormFields'])
                && \is_array($GLOBALS['TL_HOOKS']['compileFormFields'])) {
                foreach ($GLOBALS['TL_HOOKS']['compileFormFields'] as $k => $callback) {
                    // fix for MPForms which relies on compileFormFields not being called recursively
                    if ('MPForms' === $callback[0]) {
                        continue;
                    }

                    $objCallback = System::importStatic($callback[0]);
                    $subFields   = $objCallback->{$callback[1]}($subFields, $formFieldId, $form);
                }
            }

            // splice fields: replaces `mvo_nested_forms_subForm` with sub fields
            array_splice($fields, $index + $offset, 1, $subFields);
            $offset += \count($subFields) - 1;
        }

        return $fields;
    }

    /**
     * @param FormFieldModel $metaField
     *
     * @return FormFieldModel[]
     */
    private function compileSubFormFields(FormFieldModel $metaField): array
    {
        $fieldModels = FormFieldModel::findPublishedByPid((int) $metaField->mvo_nested_forms_srcForm);
        if (null === $fieldModels) {
            return [];
        }

        $fields    = [];
        $mandatory = -1 !== $metaField->mvo_nested_forms_mandatory ?
            $metaField->mvo_nested_forms_mandatory : null;

        foreach ($fieldModels as $fieldModel) {
            $field = clone $fieldModel;

            // generate prefixed pseudo name and id
            $field->name = $metaField->name . '__' . $field->name;
            $field->id   = $metaField->id . '__' . $fieldModel->id;

            // mandatory attribute
            if (null !== $mandatory) {
                $field->mandatory = $mandatory;
            }

            $fields[] = $field;
        }

        return $fields;
    }
}