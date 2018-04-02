contao-nested-forms
======================
This bundle adds the ability to use **sub forms** in the form generator
of Contao Open Source CMS to allow reusing groups of fields. After
installing and updating the database you'll find a new form field type
*Sub Form*.

#### Usage

Usage is straightforward:

 - Create a form *A* with some fields.
 - Create a form *B* and select one field to be a *Sub Form* (meta
   field).
 - In this field select *A* as source form.

The resulting form B now contains A's fields at the position of the
*Sub Form* meta field. You can select if you want to overwrite the
mandatory properties of the sub fields or keep them as they are.

#### Naming

The field names of a sub form get prefixed by the name of the meta field
and two underscores (e.g: `MySubField__FieldA`). The easiest way to
avoid collisions is by not using double underscores `__` in your field
names.

#### Multiple Sub Forms
You can use as many nested forms as you wish. Multiple nesting levels
are supported as well, just make sure you don't create self referencing
loops. Note that overwriting mandatory properties does not cascade down.


    
Installation
------------

#### Step 1: Download the Bundle  

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require mvo/contao-nested-forms
```

#### Step 2: Enable the Bundle

**Skip this point if you are using a *Managed Edition* of Contao.**

Enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new \Mvo\ContaoNestedForms\MvoContaoNestedFormsBundle(),
        );

        // ...
    }

    // ...
}
```
 
#### Step 3: Update your Database