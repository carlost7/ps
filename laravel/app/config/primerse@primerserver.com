<?php

/*
 * Funcion para guardar los datos de configuración del SDK whm
 */

return array(
    /*
     * Host server
     */
    'host' => 'rs4.websitehostserver.net',    
    /*
     * Nombre de usuario de la base de datos
     */
    'username' => 'primerse',
    /*
     * Nombre del servidor basico
     */
    'basico' => 't7start',
    /*
     * Nombre del servidor startup
     */
    'startup' => 't7start',
    /*
     * Nombre del servidor enterprise
     */
    'enterprise' => 't7start',
    /*
     * Hash de conexion
     */
    'hash'=>'9319105622c02413a635eb93df7d89b9
6fee3ad830a5b94c98bad252f9729652
b722469a1ce25711a8d282005df7ae20
5dfae56610aacb5ec283b8974954e374
a8459aed8cbc005cd651db74fdacd56d
58ddcb27f34f860e79c60d2c974b6655
c8b3b23e927158cee32e60823365caa6
756fb21743369c0877f1974e112f0d3b
06c6544d1fc00b1b66f66d2d72b8233d
8840903a55f9a19941688f9a69c394e7
8cea0e450cd63dcadee89d82afe8cfe1
650f522f68bca9c15a98a9ab5aa0c5d2
eb9ed4e52daf8ee31c08924d6ded9733
b19a4bceb7a22fd0aaa1eacd24b4bb4a
dd82d0a62df0c0c7aeab52f8c68251a9
6218554def9c738dba89e4c709bfc738
557278ef59367814cdaac7c3092892aa
e5fe92042083258b08ab7633bb0c08db
f7b1605ac232d2670278df4d6a9636c3
2ee7326b4c215c57034a705c72dd1110
3de576c203ad460305f2e53dd64d7ea1
9c49afa6489ce22dbd3df4923a6cd5ce
5fd985fea46beb77ce6b6c628aabc317
6b747c6de87275857df0625bbfdf63a2
12906850e7a9608841f5ff2fa7e4c992
2af5019c25032582044afde9b6b4bd92
92e27567d0ff36e12367b72f8370887c
a08ce3d8799282f6deab9f39d8ea0b01
a684f90b9bff8a24b64a8124c02f0290',
    
);