def print_board(board):
    print(board[1] + ' | ' + board[2] + ' | ' + board[3])
    print('---------')
    print(board[4] + ' | ' + board[5] + ' | ' + board[6])
    print('---------')
    print(board[7] + ' | ' + board[8] + ' | ' + board[9])


def win(board, mark):
    return((board[1] == board[2] == board[3] == mark) or
           (board[4] == board[5] == board[6] == mark) or
           (board[7] == board[8] == board[9] == mark) or
           (board[1] == board[4] == board[7] == mark) or
           (board[2] == board[5] == board[8] == mark) or
           (board[3] == board[6] == board[9] == mark) or
           (board[1] == board[5] == board[9] == mark) or
           (board[3] == board[5] == board[7] == mark))


def move(board, position, symbol):
    if board[position] == ' ':
        board[position] = symbol
    else:
        print("Sorry the space is occupied")


board = {x: ' ' for x in range(1, 10)}
symbol1 = input("Player1 -- Choose your Symbol ")
symbol2 = input("Player2 -- Choose your Symbol ")

print_board(board)
while True:
    position = int(input("Move for Player1 Select a position (1-9) "))
    move(board, position, symbol1)
    print_board(board)
    if win(board, symbol1):
        print("Player1 Wins!")
        break
    position = int(input("Move for Player2 Select a position (1-9) "))
    move(board, position, symbol2)
    print_board(board)
    if win(board, symbol2):
        print("Player2 Wins")
        break
