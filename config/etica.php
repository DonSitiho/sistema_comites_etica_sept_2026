<?php

return [

    /*
     * Rangos provisionales del semáforo.
     *
     * Posteriormente se reemplazarán por
     * los establecidos oficialmente
     * en la metodología de evaluación.
     */
    'semaforo' => [

        'verde_minimo' =>
            env(
                'ETICA_VERDE_MINIMO',
                90
            ),

        'amarillo_minimo' =>
            env(
                'ETICA_AMARILLO_MINIMO',
                70
            ),

    ],

];