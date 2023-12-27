<?php

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\IValueBinder;

class MyValueBinder extends DefaultValueBinder implements IValueBinder
{
    /**
     * Bind value to a cell.
     *
     * @param Cell $cell Cell to bind value to
     * @param mixed $value Value to bind in cell
     *
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     *
     */
    public function bindValue(Cell $cell, $value)
    {
        if (isValidMobileNumber($value)) {
            // Set value explicit
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            // Done!
            return true;
        }

        return parent::bindValue($cell, $value);
    }
}