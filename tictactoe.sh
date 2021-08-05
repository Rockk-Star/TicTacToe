#!/bin/bash

print() {
    echo " ${board[1]} | ${board[2]} | ${board[3]}"
    echo "-----------"
    echo " ${board[4]} | ${board[5]} | ${board[6]}"
    echo "-----------"
    echo " ${board[7]} | ${board[8]} | ${board[9]}"
}

# $1 = symbol
win() {
    if [[ (${board[1]} == ${board[2]} && ${board[2]} == ${board[3]} && ${board[3]} == $1) || (${board[4]} == ${board[5]} && ${board[5]} == ${board[6]} && ${board[6]} == $1) || (${board[7]} == ${board[8]} && ${board[8]} == ${board[9]} && ${board[9]} == $1) || (${board[1]} == ${board[4]} && ${board[4]} == ${board[7]} && ${board[7]} == $1) || (${board[2]} == ${board[5]} && ${board[5]} == ${board[8]} && ${board[8]} == $1) || (${board[3]} == ${board[6]} && ${board[6]} == ${board[9]} && ${board[9]} == $1) || (${board[1]} == ${board[5]} && ${board[5]} == ${board[9]} && ${board[9]} == $1) || (${board[3]} == ${board[5]} && ${board[5]} == ${board[7]} && ${board[7]} == $1) ]]; then
        return 0
    else
        return 1
    fi
}

# $1 = position $2 = symbol
move() {
    local position=$1
    while true; do
        if (($position < 1 || $position > 9)); then
            echo "Out of range"
        elif [[ ${board[$position]} == ' ' ]]; then
            board[$position]=$2
            break
        else
            echo "Sorry the space was occupied"
        fi
        read -p "Please Enter a Valid Position " position
    done
}

hasEmptySpace() {
    if [[ ${board[1]} == " " || ${board[2]} == " " || ${board[3]} == " " || ${board[4]} == " " || ${board[5]} == " " || ${board[6]} == " " || ${board[7]} == " " || ${board[8]} == " " || ${board[9]} == " " ]]; then
        return 1
    else
        return 0
    fi
}

for i in {1..9}; do
    board[$i]=" "
done

read -p "Player1 -- Choose your Symbol " symbol1
read -p "Player2 -- Choose your Symbol " symbol2

while [[ $symbol1 == $symbol2 ]]; do
    read -p "Please Choose Another Symbol " symbol2
done

print

while true; do

    read -p "Move for Player1 Select a Position (1-9) " position
    move $position $symbol1
    print

    if win $symbol1; then
        echo "Player1 Wins!"
        break
    fi

    if hasEmptySpace; then
        echo "It's a Tie!"
        break
    fi

    read -p "Move for Player2 Select a Position (1-9) " position
    move $position $symbol2
    print

    if win $symbol2; then
        echo "Player2 Wins!"
        break
    fi

done
