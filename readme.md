# WEB CHESS API

Веб-сервис, который даёт возможность провести шахматную партию.

## API
Методы:
* Начать новую партию `new_game`
* Получить статус партии `game_state` 
* Получить состояние партии `game_status`
* Сделать ход `new_move`

### Метод new_game

Для того, чтобы обратиться к методу API `new_game` Вам необходимо выполнить GET/PUT/POST запрос такого вида:  
`http://домен/new_game`, к примеру `http://localhost:8080/new_game`

В ответ на такой запрос Вы получите ответ в формате JSON:
```
{
    "ok": true,
    "message": "New game started"
}
```
если новая партия была успешно начата, или 
```
{
    "ok": false,
    "message": "Сообщение об ошибке"
}
```
если новая партия начата не была
### Метод game_status
Для того, чтобы обратиться к методу API `game_status` Вам необходимо выполнить запрос такого вида:  
`http://домен/game_status`, к примеру `http://localhost:8080/game_status`

В ответ на такой запрос Вы получите один из трех ответов: 
```
1) "The game is on"
2) "Game over, white win"
3) "Game over, black win"
```
### Метод game_state
Для того, чтобы обратиться к методу API `game_state` Вам необходимо выполнить запрос такого вида:  
`http://домен/game_state`, к примеру `http://localhost:8080/game_state`
В ответ на такой запрос Вы получите содержимое файла `data/gameState.json`

Пример того, что Вы получите, если выполните запрос после того, как начнете новую партию [здесь](https://github.com/FadeevSergey/WEB_CHEES_API/blob/master/src/data/gameState.json)

nextMove - хранит цвет того, кто ходит следующий  
gameStatus - хранит статус партии  
board - хранит состояни шахматной доски

board представляет собой JSON, в котором ключи "1" - "8" отвечают за номер строки. Значения для этих ключей - JSON, хранящий фигуры, находящиеся на соответствующих строках шахматной доски. 

Пример JSON, хранящего фигуры строки №1 в начале игры. 
```
 {
  "a": {
    "name": "Rook",
    "color": "white"
  },
  "h": {
    "name": "Rook",
    "color": "white"
  },
  "b": {
    "name": "Knight",
    "color": "white"
  },
  "g": {
    "name": "Knight",
    "color": "white"
  },
  "c": {
    "name": "Bishop",
    "color": "white"
  },
  "f": {
    "name": "Bishop",
    "color": "white"
  },
  "d": {
    "name": "Queen",
    "color": "white"
  },
  "e": {
    "name": "King",
    "color": "white"
  }
}
```
Если клетка пуста, то ее значение `null`
### Метод new_move

Для того, чтобы обратиться к методу API `new_move` Вам необходимо выполнить запрос такого вида:  
http://домен/new_move?описание_хода`
описание_хода - JSON следующего формата:
```
{
    "from":{
        "line":строка,
        "column":столбец
    },
    "to":{
        "line":строка,
        "column":"столбец"
    }
}
```
Пример:
JSON, описывающих ход E2 - E4
```
{
    "from":{
        "line":2,
        "column":"e"
    },
    "to":{
        "line":4,
        "column":"4"
    }
}
```
Для такого хода запрос будет иметь вид:  
`http://домен/new_move?{"from":{"line":7,"column":"h"},"to":{"line":5,"column":"h"}}`, к примеру:  
`http://localhost:8080/new_move?{"from":{"line":7,"column":"h"},"to":{"line":5,"column":"h"}}`

В ответ на такой запрос Вы получите ответ в формате JSON:
```
{
    "ok": true,
    "message": "Update was successful"
}
```
если новый ход был сделан, или 
```
{
    "ok": false,
    "message": "Сообщение об ошибке"
}
```
если новый ход сделан не был. 
 
Возможные сообщения об ошибке:  

* Input is not json
* Invalid json
* Invalid json. Pairs key value is not two
* Invalid json. Json must contain a key 'from' and 'to'
* Invalid json. 'from' and 'to' should contains 2 pair key-value
* Invalid start call
* Invalid finish call
* Trying to resemble a non-existent figure
* The move must be made by the other side
* It is impossible to make a move to the same cage
* You can’t go to the field occupied by your figure
* A bishop cannot make such a move
* A bishop cannot jump over shapes
* A king cannot make such a move
* A knight cannot make such a move
* A pawn cannot make such a move
* A pawn cannot jump over shapes
* A pawn cannot move forward into the cell in which the chess piece stands
* A move not forward is possible only to take someone else's piece
* A queen cannot jump over shapes
* A queen cannot make such a move
* A rook cannot make such a move
* A rook cannot jump over shapes
* A move is impossible. Checkmate declared to the white/black king
* As a result of the move, the king will be under the check

Если белая пешка достигает линии №8, или черная пешка достигает линии №1, то она становится ферзем.
