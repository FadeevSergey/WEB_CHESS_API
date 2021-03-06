<?php

require_once __DIR__ . '/../services/GameService.php';
require_once __DIR__ . '/../models/GameModel.php';
require_once __DIR__ . '/../chessObjects/GameState.php';

class GameController
{
    /**
     * return [
     *     'ok' => res,
     *     'message' => mes,
     * ]
     *
     * res == true and mes = 'New game started' if was started new game
     *
     * @return array
     */
    public static function newGame()
    {
        try {
            file_put_contents('data/gameState.json', json_encode(new GameState()));
            return [
                'ok' => true,
                'message' => 'New game started',
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * newMove return [
     *     'ok' => res,
     *     'message' => mes,
     * ]
     *
     * res == true and mes = 'Update was successful' if new move has been made
     * else res == false and mes contains the reason why the move is impossible
     *
     * @param string $gameData
     * @return array
     */
    public static function newMove($gameData)
    {
        try {
            $validateInputResult = GameModel::validateInput($gameData);
            if (!$validateInputResult['ok']) {
                return $validateInputResult;
            }

            $gameDataArray = json_decode($gameData, true);
            $gameState = json_decode(file_get_contents('data/gameState.json'), true);

            $validateMoveResult = GameService::validateMove($gameDataArray, $gameState);
            if (!$validateMoveResult['ok']) {
                return $validateMoveResult;
            }

            if (GameService::kingWillInCheck($gameDataArray, $gameState)) {
                if (GameService::kingInCheckmate($gameState, $gameState['nextMove'])) {
                    $winColor = $gameState['nextMove'] == 'white' ? 'black' : 'white';
                    $loseColor = $gameState['nextMove'];
                    GameService::setGameStatus("Game over, $winColor win");

                    return [
                        'ok' => false,
                        'message' => "A move is impossible. Checkmate declared to the $loseColor king",
                    ];

                } else {
                    return [
                        'ok' => false,
                        'message' => 'As a result of the move, the king will be under the check',
                    ];
                }
            }

            return GameService::updateBoard($gameData);

        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Exception in GameController::newMove with message: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Return json with game state
     *
     * @return string
     */

    public static function getGameState()
    {
        return file_get_contents('data/gameState.json');
    }

    /**
     * Return game status
     *
     * @return string
     */
    public static function getGameStatus()
    {
        return json_decode(file_get_contents('data/gameState.json'), true)['gameStatus'];
    }
}