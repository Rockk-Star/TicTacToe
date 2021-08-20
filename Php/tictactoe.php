<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
$last = null;
$message = "";

# 0 is the default value
# 1 - X
# 2 - O

$board = array();
$defaultBoard = array();
$stage = 1;

for ($i = 1; $i <= 9; $i++) {
    $board["name" . $i] = 0;
    $defaultBoard["name" . $i] = 0;
}

function boardHelper($value, $name)
{
    // name parameter should be unique

    $cell = '';

    if ($value == 1) {
        $symbol = 'X';
    } elseif ($value == 2) {
        $symbol = 'O';
    } else {
        $symbol = 'select';
    }

    if ($value == 1 || $value == 2) {
        $cell .= "<input type='hidden' name='" . $name . "' value='" . $symbol . "'/>";
        $cell .= "<select disabled>";
    } else {
        $cell .= "<select name=" . $name . " >";
    }
    $cell .= "<option>select</option>";

    if ($value == 1) {
        $cell .= "<option selected>X</option>";
    } else {
        $cell .= "<option>X</option>";
    }

    if ($value == 2) {
        $cell .= "<option selected>O</option>";
    } else {
        $cell .= "<option>O</option>";
    }
    $cell .= "</select>";
    return $cell;
}

function win($board, $lastSymbolValue)
{
    if ($lastSymbolValue == 1) {
        $symbolValue = 2;
    } elseif ($lastSymbolValue == 2) {
        $symbolValue = 1;
    } else {
        return false;
    }
    if (($board['name1'] == $board['name2'] && $board['name2'] == $board['name3'] && $board['name3'] == $symbolValue) ||
        ($board['name4'] == $board['name5'] && $board['name5'] == $board['name6'] && $board['name6'] == $symbolValue) ||
        ($board['name7'] == $board['name8'] && $board['name8'] == $board['name9'] && $board['name9'] == $symbolValue) ||
        ($board['name1'] == $board['name4'] && $board['name4'] == $board['name7'] && $board['name7'] == $symbolValue) ||
        ($board['name2'] == $board['name5'] && $board['name5'] == $board['name8'] && $board['name8'] == $symbolValue) ||
        ($board['name3'] == $board['name6'] && $board['name6'] == $board['name9'] && $board['name9'] == $symbolValue) ||
        ($board['name1'] == $board['name5'] && $board['name5'] == $board['name9'] && $board['name9'] == $symbolValue) ||
        ($board['name3'] == $board['name5'] && $board['name5'] == $board['name7'] && $board['name7'] == $symbolValue)
    ) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['turn'])) {
    $board = isset($_POST['board']) ? json_decode($_POST['board'], true) : [];
    $last = isset($_POST['last']) ? $_POST['last'] : null;

    $submittedBoard = [];
    foreach ($_POST as $key => $value) {
        if (!in_array($key, ['last', 'turn', 'board', 'stage'])) {
            if ($value == 'X') {
                $submittedBoard[$key] = 1;
            } elseif ($value == 'O') {
                $submittedBoard[$key] = 2;
            } else {
                $submittedBoard[$key] = 0;
            }
        }
    }

    $changes = [];
    foreach ($board as $key => $value) {
        if ($value != $submittedBoard[$key]) {
            $changes[] = $submittedBoard[$key];
        }
    }

    if (count($changes) > 1) {
        $message .= "Nope! Can't play more than once";
        $stage = $_POST['stage'];
    } elseif ($last !=  null  && $last == $changes[0]) {
        $message .= "You cannot play twice";
        $stage = $_POST['stage'];
    } elseif (win($submittedBoard, $last)) {
        if ($last == 1) {
            $message .= "The winner is the player with Symbol = O<br>";
        } elseif ($last == 2) {
            $message .= "The winner is the player with Symbol = X<br>";
        }
        $message .= "Game has been restarted";
        $board = $defaultBoard;
        $stage = 1;
    } elseif ($_POST['stage'] == "9") {
        $message .= "It's a Tie. Game has been restarted";
        $board = $defaultBoard;
        $stage = 1;
    } else {
        // Assuming that the user never gives an empty input
        $last = $changes[0];
        $board = $submittedBoard;
        $stage = (int)$_POST['stage'] + 1;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic-Tac-Toe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            background-size: cover;
            background-color: #FCF6F5FF;
            display: flex;
            flex-direction: column;
            height: 100vh;
            width: 100vw;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .message {
            margin: 10px;
        }

        select {
            background-color: #89ABE3FF;
            width: 100px;
        }

        option {
            background-color: #89ABE3FF;
        }

        .title {
            margin: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class='title'>Welcome to TicTacToe</h1>
        <form action="" method="post">
            <?php if ($message) { ?>
                <p class="message"><?php echo $message; ?></p>
            <?php } ?>
            <input name="board" type="hidden" value="<?php echo htmlspecialchars(json_encode($board)) ?>">
            <input name="last" type="hidden" value="<?php echo $last; ?>">
            <input type="hidden" name="stage" value="<?php echo $stage; ?>">
            <table>
                <?php
                ksort($board);
                $count = 0;
                foreach ($board as $key => $value) { ?>
                    <tr>
                        <?php $count++;
                        echo boardHelper($value, $key);
                        if ($count % 3 == 0) {
                            echo "<br>";
                        }
                        ?>
                    </tr>
                <?php } ?>
            </table>
            <button name="turn" value="submit">End Turn</button>
        </form>
    </div>
</body>

</html>
