<?php

//declare(strict_types=1);

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

            $subFields = $this->getSubFormFields(
                (int) $field->mvo_nested_forms_srcForm,
                $field->name,
                -1 !== $field->mvo_nested_forms_mandatory ? $field->mvo_nested_forms_mandatory : null
            );

            // HOOK: compile sub form fields
            if (isset($GLOBALS['TL_HOOKS']['compileFormFields'])
                && \is_array($GLOBALS['TL_HOOKS']['compileFormFields'])) {
                foreach ($GLOBALS['TL_HOOKS']['compileFormFields'] as $k => $callback) {
                    // fix for MPForms which relies on compileFormFields not being called recursively
                    if ('MPForms' === $callback[0]) {
                        continue;
                    }

                    $objCallback = System::importStatic($callback[0]);
                    $subFields   = $objCallback->{$callback[1]}($subFields, $formFieldId . '_x', $form);
                }
            }

            // splice fields: replaces `mvo_nested_forms_subForm` with sub fields
            array_splice($fields, $index + $offset, 1, $subFields);
            $offset += \count($subFields) - 1;
        }

        return $fields;
    }

    /**
     * @param int       $id
     * @param string    $prefix
     * @param bool|null $mandatory
     *
     * @return FormFieldModel[]
     */
    private function getSubFormFields(int $id, string $prefix, ?bool $mandatory): array
    {
        $fieldModels = FormFieldModel::findPublishedByPid($id);
        if (null === $fieldModels) {
            return [];
        }

        $fields = [];
        foreach ($fieldModels as $fieldModel) {
            // exclude submit fields
            if ('submit' !== $fieldModel->type) {
                $field = clone $fieldModel;

                $field->name = $prefix . '__' . $fieldModel->name;
                if (null !== $mandatory) {
                    $field->mandatory = $mandatory;
                }

                $fields[] = $field;
            }
        }

        return $fields;
    }
}