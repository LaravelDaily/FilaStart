FilaStart is built with customization in mind. You can create your own custom fields to suit your needs. Here's a quick guide on how to make a custom field:

## Defining the Field

First, we need to tell our system that this field exists. To do this, open up:

**app/Enums/CrudFieldTypes.php**

And add a new field type:

```php
// ...
const CUSTOM_FIELD = 'custom_field';
// ...
```

Of course, remember to add it to the `getLabel()` method, as it will automatically populate the select field.

## Creating the Field Class

Next, we need a new class for our field. Create a new file in:

**systems/generators/filament3/src/Generators/Fields**

**Note:** You can copy and modify one of the existing fields to suit your needs.

Once that is done - you can override methods as you need. But here's a few important ones:

```php
// Class to use in the form
protected string $formComponentClass = 'DatePicker';

// Class to use in the table
protected string $tableColumnClass = 'TextColumn';

// The key to use in the form
protected function resolveFormComponent(): void
{
    $this->formKey = $this->field->key;
}

// The key to use in the table
protected function resolveTableColumn(): void
{
    $this->tableKey = $this->field->key;
}
```

Once this is done, we have another step to take - register the field in the generator.

Open up `systems/generators/filament3/src/Generators/Fields/RetrieveGeneratorForField.php` and add your field to the match statement.

## Using the Field

You can now use your field in the CRUD editor. Select the "Custom Field" type and fill in the form as you would with any other field.

## Testing

We strongly recommend testing your field before using it in production. To do this, create a new file in `tests/Feature/Filament3/Fields` and make a test for your field.

## Other Customizations

When creating a new field, remember to look at `systems/generators/filament3/src/Generators/Fields/BaseField.php`, as this is the base class for all fields. You can override any method you need in your custom field.