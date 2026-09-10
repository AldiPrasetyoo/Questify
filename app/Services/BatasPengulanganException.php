<?php

namespace App\Services;

/**
 * Dilempar secara internal oleh InterpreterKode saat anggaran langkah global
 * (self::LANGKAH_MAKS) habis. Ini BUKAN kegagalan sistem — ini mekanisme
 * pengaman yang disengaja untuk menghentikan program siswa yang tidak pernah
 * berhenti, sehingga proses PHP tetap terkendali dan tidak pernah menggantung.
 */
class BatasPengulanganException extends \RuntimeException
{
}
