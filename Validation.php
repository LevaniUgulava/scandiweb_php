<?php

namespace Validation;

class Validation
{
    public static function validateInfo($data)
    {
        $errors = [];
 
        
        if (empty($data->sku)) {
            $errors['sku'] = 'The SKU is required.';
        }

        if (empty($data->name)) {
            $errors['name'] = 'The name is required.';
        }

        if (empty($data->price)) {
            $errors['price'] = 'The price is required.';
        } elseif (!is_numeric($data->price)) {
            $errors['price'] = 'The price must be a number.';
        }

        if (empty($data->type)) {
            $errors['type'] = 'The product type is required.';
        } elseif (!in_array($data->type, ['Book', 'Furniture', 'DVD'])) {
            $errors['type'] = 'The product type must be either Book, Furniture, or DVD.';
        }

        if (isset($data->type) && $data->type === 'DVD' && empty($data->details->size)) {
            $errors['details'] = 'Size is required for DVDs.';
        }

        if (isset($data->type) && $data->type === 'Book' && empty($data->details->weight)) {
            $errors['details'] = 'Weight is required for Books.';
        }

        if (isset($data->type) && $data->type === 'Furniture') {
            if (empty($data->details->width)) {
                $errors['details_width'] = 'Width is required for Furniture.';
            }
            if (empty($data->details->height)) {
                $errors['details_height'] = 'Height is required for Furniture.';
            }
            if (empty($data->details->length)) {
                $errors['details_length'] = 'Length is required for Furniture.';
            }
        }

        return $errors;
    }
}
