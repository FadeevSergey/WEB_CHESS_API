<?php

require_once('/Users/Fadeev/Downloads/vk/src/chessObjects/Figure.php');

class King extends Figure
{
    // guaranteed that from and to are on the board
    public static function validateMove($newTurn, &$board)
    {
        // TODO: Implement validateMove() method.
        return true;
    }

    public function jsonSerialize()
    {
        return [
            'name' => 'King',
            'color' => $this->color,
        ];
    }
}