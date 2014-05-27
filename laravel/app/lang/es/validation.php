<?php

return array(
      /*
        |--------------------------------------------------------------------------
        | Validation Language Lines
        |--------------------------------------------------------------------------
        |
        | The following language lines contain the default error messages used by
        | the validator class. Some of these rules have multiple versions such
        | as the size rules. Feel free to tweak each of these messages here.
        |
       */

      "accepted" => "El campo :attribute debe aceptarse.",
      "active_url" => "El campo :attribute no es una URL valida.",
      "after" => "El campo :attribute debe ser una fecha posterior a :date.",
      "alpha" => "El campo :attribute solo puede contener letras.",
      "alpha_dash" => "El campo :attribute solo puede contener letras, numeros y guiones.",
      "alpha_num" => "El campo :attribute solo puede contener letras y numeros.",
      "array" => "El campo :attribute debe ser un arreglo.",
      "before" => "El campo :attribute debe ser una fecha anterior a :date.",
      "between" => array(
            "numeric" => "El campo :attribute debe estar entre :min y :max.",
            "file" => "El campo :attribute debe estar :min y :max kilobytes.",
            "string" => "El campo :attribute debe estar entre :min y :max characters.",
            "array" => "El campo :attribute debe estar entre :min y :max items.",
      ),
      "confirmed" => "El campo :attribute no coincide.",
      "date" => "El campo :attribute no contiene una fecha valida.",
      "date_format" => "El campo :attribute no coincide con el formato :format.",
      "different" => "El campo :attribute y :other deben ser diferentes.",
      "digits" => "El campo :attribute debe tener :digits digitos.",
      "digits_between" => "El campo :attribute debe estar entre :min y :max digitos.",
      "email" => "El campo :attribute debe ser una dirección de correo valida",
      "exists" => "El campo :attribute seleccionado es invalido.",
      "image" => "El campo :attribute debe ser una imagen.",
      "in" => "El campo :attribute seleccionado es invalido.",
      "integer" => "El campo :attribute debe ser un entero.",
      "ip" => "El campo :attribute debe contener una IP valida.",
      "max" => array(
            "numeric" => "El campo :attribute no puede ser mas grande que :max.",
            "file" => "El campo :attribute no puede ser mas grande que :max kilobytes.",
            "string" => "El campo :attribute no puede ser mas grande que :max caracteres.",
            "array" => "El campo :attribute no puede contener mas de :max items.",
      ),
      "mimes" => "El campo :attribute debe contener un archivo del tipo: :values.",
      "min" => array(
            "numeric" => "El campo :attribute debe ser al menos de :min.",
            "file" => "El campo :attribute debe ser al menos de :min kilobytes.",
            "string" => "El campo :attribute debe ser al menos de :min caracteres.",
            "array" => "El campo :attribute debe ser al menos de :min items.",
      ),
      "not_in" => "El campo seleccionado :attribute es invalido.",
      "numeric" => "El campo :attribute debe ser un numero.",
      "regex" => "El formato del campo :attribute es invalido.",
      "required" => "El campo :attribute es necesario.",
      "required_if" => "El campo :attribute es necesario cuando :other es :value.",
      "required_with" => "El campo :attribute es necesario cuando :values esta seleccionado.",
      "required_with_all" => "El campo :attribute es necesario cuando :values esta presente.",
      "required_without" => "El campo :attribute es necesario cuando :values no esta presente.",
      "required_without_all" => "El campo :attribute es necesario cuando ninguno de estos :values esta presente.",
      "same" => "El campo :attribute y :other deben coincidir.",
      "size" => array(
            "numeric" => "El campo :attribute debe medir :size.",
            "file" => "El campo :attribute debe medir :size kilobytes.",
            "string" => "El campo :attribute debe medir :size caracteres.",
            "array" => "El campo :attribute debe contener :size items.",
      ),
      "unique" => "El campo :attribute ya existe en el sistema.",
      "url" => "El formato para el campo :attribute is invalido.",
      /*
        |--------------------------------------------------------------------------
        | Custom Validation Language Lines
        |--------------------------------------------------------------------------
        |
        | Here you may specify custom validation messages for attributes using the
        | convention "attribute.rule" to name the lines. This makes it quick to
        | specify a specific custom language line for a given attribute rule.
        |
       */
      'custom' => array(
            'attribute-name' => array(
                  'rule-name' => 'custom-message',
            ),
      ),
      /*
        |--------------------------------------------------------------------------
        | Custom Validation Attributes
        |--------------------------------------------------------------------------
        |
        | The following language lines are used to swap attribute place-holders
        | with something more reader friendly such as E-Mail Address instead
        | of "email". This simply helps us make messages a little cleaner.
        |
       */
      'attributes' => array(),
);
