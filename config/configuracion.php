<?php

function envArray($key)
{
    $value = env($key, '[]');
    return array_map('intval', json_decode($value, true) ?? []);
}

return [
    'ids_solo_agua' => envArray('IDS_SOLO_AGUA'),
    'ids_sin_agua' => envArray('IDS_SIN_AGUA'),
    'ids_aveces_producto_aveses_no' => envArray('IDS_AVECES_PRODUCTO_AVESES_NO'),
];
