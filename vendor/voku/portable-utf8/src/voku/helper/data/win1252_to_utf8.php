<?php

static $data = array(
    0x80 => "\xe2\x82\xac", # €
    0x82 => "\xe2\x80\x9a", # ‚
    0x83 => "\xc6\x92",     # ƒ
    0x84 => "\xe2\x80\x9e", # „
    0x85 => "\xe2\x80\xa6", # …
    0x86 => "\xe2\x80\xa0", # †
    0x87 => "\xe2\x80\xa1", # ‡
    0x88 => "\xcb\x86",     # ˆ
    0x89 => "\xe2\x80\xb0", # ‰
    0x8a => "\xc5\xa0",     # Š
    0x8b => "\xe2\x80\xb9", # ‹
    0x8c => "\xc5\x92",     # Œ
    0x8e => "\xc5\xbd",     # Ž
    0x91 => "\xe2\x80\x98", # ‘
    0x92 => "\xe2\x80\x99", # ’
    0x93 => "\xe2\x80\x9c", # “
    0x94 => "\xe2\x80\x9d", # ”
    0x95 => "\xe2\x80\xa2", # •
    0x96 => "\xe2\x80\x93", # –
    0x97 => "\xe2\x80\x94", # —
    0x98 => "\xcb\x9c",     # ˜
    0x99 => "\xe2\x84\xa2", # ™
    0x9a => "\xc5\xa1",     # š
    0x9b => "\xe2\x80\xba", # ›
    0x9c => "\xc5\x93",     # œ
    0x9e => "\xc5\xbe",     # ž
    0x9f => "\xc5\xb8",     # Ÿ
    0xa0 => "\xc2\xa0",     #
    0xa1 => "\xc2\xa1",     # ¡
    0xa2 => "\xc2\xa2",     # ¢
    0xa3 => "\xc2\xa3",     # £
    0xa4 => "\xc2\xa4",     # ¤
    0xa5 => "\xc2\xa5",     # ¥
    0xa6 => "\xc2\xa6",     # ¦
    0xa7 => "\xc2\xa7",     # §
    0xa8 => "\xc2\xa8",     # ¨
    0xa9 => "\xc2\xa9",     # ©
    0xaa => "\xc2\xaa",     # ª
    0xab => "\xc2\xab",     # «
    0xac => "\xc2\xac",     # ¬
    0xad => "\xc2\xad",     # ­
    0xae => "\xc2\xae",     # ®
    0xaf => "\xc2\xaf",     # ¯
    0xb0 => "\xc2\xb0",     # °
    0xb1 => "\xc2\xb1",     # ±
    0xb2 => "\xc2\xb2",     # ²
    0xb3 => "\xc2\xb3",     # ³
    0xb4 => "\xc2\xb4",     # ´
    0xb5 => "\xc2\xb5",     # µ
    0xb6 => "\xc2\xb6",     # ¶
    0xb7 => "\xc2\xb7",     # ·
    0xb8 => "\xc2\xb8",     # ¸
    0xb9 => "\xc2\xb9",     # ¹
    0xba => "\xc2\xba",     # º
    0xbb => "\xc2\xbb",     # »
    0xbc => "\xc2\xbc",     # ¼
    0xbd => "\xc2\xbd",     # ½
    0xbe => "\xc2\xbe",     # ¾
    0xbf => "\xc2\xbf",     # ¿
    0xc0 => "\xc3\x80",     # À
    0xc1 => "\xc3\x81",     # Á
    0xc2 => "\xc3\x82",     # Â
    0xc3 => "\xc3\x83",     # Ã
    0xc4 => "\xc3\x84",     # Ä
    0xc5 => "\xc3\x85",     # Å
    0xc6 => "\xc3\x86",     # Æ
    0xc7 => "\xc3\x87",     # Ç
    0xc8 => "\xc3\x88",     # È
    0xc9 => "\xc3\x89",     # É
    0xca => "\xc3\x8a",     # Ê
    0xcb => "\xc3\x8b",     # Ë
    0xcc => "\xc3\x8c",     # Ì
    0xcd => "\xc3\x8d",     # Í
    0xce => "\xc3\x8e",     # Î
    0xcf => "\xc3\x8f",     # Ï
    0xd0 => "\xc3\x90",     # Ð
    0xd1 => "\xc3\x91",     # Ñ
    0xd2 => "\xc3\x92",     # Ò
    0xd3 => "\xc3\x93",     # Ó
    0xd4 => "\xc3\x94",     # Ô
    0xd5 => "\xc3\x95",     # Õ
    0xd6 => "\xc3\x96",     # Ö
    0xd7 => "\xc3\x97",     # ×
    0xd8 => "\xc3\x98",     # Ø
    0xd9 => "\xc3\x99",     # Ù
    0xda => "\xc3\x9a",     # Ú
    0xdb => "\xc3\x9b",     # Û
    0xdc => "\xc3\x9c",     # Ü
    0xdd => "\xc3\x9d",     # Ý
    0xde => "\xc3\x9e",     # Þ
    0xdf => "\xc3\x9f",     # ß
    0xe0 => "\xc3\xa0",     # à
    0xe1 => "\xa1",         # á
    0xe2 => "\xc3\xa2",     # â
    0xe3 => "\xc3\xa3",     # ã
    0xe4 => "\xc3\xa4",     # ä
    0xe5 => "\xc3\xa5",     # å
    0xe6 => "\xc3\xa6",     # æ
    0xe7 => "\xc3\xa7",     # ç
    0xe8 => "\xc3\xa8",     # è
    0xe9 => "\xc3\xa9",     # é
    0xea => "\xc3\xaa",     # ê
    0xeb => "\xc3\xab",     # ë
    0xec => "\xc3\xac",     # ì
    0xed => "\xc3\xad",     # í
    0xee => "\xc3\xae",     # î
    0xef => "\xc3\xaf",     # ï
    0xf0 => "\xc3\xb0",     # ð
    0xf1 => "\xc3\xb1",     # ñ
    0xf2 => "\xc3\xb2",     # ò
    0xf3 => "\xc3\xb3",     # ó
    0xf4 => "\xc3\xb4",     # ô
    0xf5 => "\xc3\xb5",     # õ
    0xf6 => "\xc3\xb6",     # ö
    0xf7 => "\xc3\xb7",     # ÷
    0xf8 => "\xc3\xb8",     # ø
    0xf9 => "\xc3\xb9",     # ù
    0xfa => "\xc3\xba",     # ú
    0xfb => "\xc3\xbb",     # û
    0xfc => "\xc3\xbc",     # ü
    0xfd => "\xc3\xbd",     # ý
    0xfe => "\xc3\xbe",     # þ
);

$result =& $data;
unset($data);
return $result;
