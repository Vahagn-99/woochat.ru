<?php

namespace App\Services\AmoCRM;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\BaseCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\BaseCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\SelectCustomFieldValueModel;

class CustomFieldAdapter
{
    /**
     * @param mixed $adaptable
     * @return CustomFieldsValuesCollection
     */
    public function adapt(mixed $adaptable): CustomFieldsValuesCollection
    {
        $customFieldsValuesCollection = new CustomFieldsValuesCollection();

        foreach ($adaptable as $field) {
            $fieldValueModel = $this->getFieldValueModel($field);
            $fieldValuesModel = $this->getFieldValuesModel($field, $fieldValueModel);
            $customFieldsValuesCollection->add($fieldValuesModel);
        }

        return $customFieldsValuesCollection;
    }

    /**
     * @param array $field
     * @return BaseCustomFieldValueModel
     */
    private function getFieldValueModel(array $field): BaseCustomFieldValueModel
    {
        /** @extends BaseCustomFieldValueModel */
        $fieldValueModel = $field['custom_field_value_model'];

        if ($fieldValueModel instanceof MultitextCustomFieldValueModel) {
            $fieldValueModel->setEnum($field['custom_field_enum']);
            $fieldValueModel->setValue($field['value']);
        }
        if ($fieldValueModel instanceof SelectCustomFieldValueModel) {
            $fieldValueModel->setEnumId((int)$field['enum_id']);
        } else {
            $fieldValueModel->setValue($field['value']);
        }

        return $fieldValueModel;
    }

    /**
     * @param array $field
     * @param BaseCustomFieldValueModel $fieldValueModel
     * @return BaseCustomFieldValuesModel
     */
    private function getFieldValuesModel(
        array $field,
        BaseCustomFieldValueModel $fieldValueModel
    ): BaseCustomFieldValuesModel {
        /** @extends BaseCustomFieldValuesModel */
        $fieldValuesModel = $field['custom_field_values_model'];
        if ($fieldValuesModel instanceof MultitextCustomFieldValuesModel) {
            $fieldValuesModel->setFieldCode($field['custom_field_code']);
        } else {
            $fieldValuesModel->setFieldId($field['id']);
        }

        /** @extends BaseCustomFieldValueCollection */
        $fieldValuesCollection = new BaseCustomFieldValueCollection();
        $fieldValuesCollection->add($fieldValueModel);

        $fieldValuesModel->setValues($fieldValuesCollection);

        return $fieldValuesModel;
    }
}
