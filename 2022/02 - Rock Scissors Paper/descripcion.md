# Descripcion

Hacer un programa que lee un fichero de texto con estrategias de
de partidas de *piedra, papel y tijera*.

Donde la secuencia de resultados es la siguiente:

Elfo/Yo | Rock | Paper | Scissors
---------------------------------
**Rock** | Draw | Win | Defeat
**Paper** | Defeat | Draw | Win
**Scissors** | Win | Defeat | Draw

Hay un código de movimientos que se asocia al movimiento del elfo y el tuyo:

## Parte 1

Elemento | Elfo | Tú
--------------------
Rock | `A` | `X`
Paper | `B` | `Y`
Scissors | `C` | `Z`

Además, hay una puntuación dependiente del elemento elegido y el resultado de la ronda.

Elemento | Puntuación
---------------------
Rock | `1`
Paper | `2`
Scissors | `3`

Resultado | Puntuación
----------------------
Win | 6
Draw | 3
Lost | 0

## Parte 2

El segundo código de cada jugada indica el resultado final que se debe llegar, y por tanto, indirectamente que elemento
elegir en función de lo que haya decidido el elfo:

Resultado | Código
------------------
Derrota | 'X'
Empate | 'Y'
Victoria | 'Z'
