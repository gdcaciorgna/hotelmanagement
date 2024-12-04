<?php

return [
    'confirmed' => 'No coinciden las contraseñas enviadas.',
    'required' => 'El campo :attribute es obligatorio.',
    'date' => 'El campo :attribute debe ser una fecha válida.',
    'email' => 'El campo :attribute debe ser una dirección de correo válida.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'min' => [
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',

    ],
    'max' => [
        'numeric' => 'El campo :attribute no debe ser mayor a :max.',
    ],
    'exists' => 'El campo :attribute seleccionado no es válido.',
    'after_or_equal' => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
    'after' => 'El campo :attribute debe ser una fecha posterior a :date.',
    'attributes' => [
        
        'startDate' => 'fecha de inicio',
        'agreedEndDate' => 'fecha de fin pactada',
        'numberOfPeople' => 'número de huéspedes',
        'rate_id' => 'tarifa',
        'user_id' => 'huésped',
        'room_code' => 'código de habitación',

        'docType' => 'tipo de documento',
        'numDoc' => 'número de documento',
        'firstName' => 'nombre',
        'lastName' => 'apellido',
        'email' => 'correo electrónico',
        'phone' => 'teléfono',
        'address' => 'dirección',
        'bornDate' => 'fecha de nacimiento',
        'userType' => 'tipo de usuario',
        'weekdayStartWorkHours' => 'hora de inicio laboral',
        'weekdayEndWorkHours' => 'hora de fin laboral',
        'startEmploymentDate' => 'fecha de inicio laboral',
        'status' => 'estado del usuario',
        'disabledStartDate' => 'fecha de inhabilitación',
        'disabledReason' => 'motivo de inhabilitación',
        'newPassword' => 'nueva contraseña',
        'newPassword_confirmation' => 'confirmación de contraseña',
    ],
    'custom' => [
        'startDate' => [
            'after_or_equal' => 'El campo fecha de inicio debe ser una fecha posterior o igual a hoy.',
        ],
    ],

];
