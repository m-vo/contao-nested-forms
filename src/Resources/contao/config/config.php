<?php

declare(strict_types=1);

use Mvo\ContaoNestedForms\Form\SubForm;

/*
 * Nested Forms Bundle for Contao Open Source CMS
 *
 * @copyright  Moritz Vondano
 * @license    MIT
 * @link       https://github.com/m-vo/contao-nested-forms
 *
 */

$GLOBALS['TL_FFL']['mvo_nested_forms_subForm'] = SubForm::class;
$GLOBALS['TL_HOOKS']['compileFormFields'][]   = ['mvo_contao_nested_forms.form_compiler', 'onInject'];
