<?php
require("0conn.php");
$conversation = array();

// Check if the "Go to Dashboard" button is clicked
if (isset($_POST['go_to_dashboard'])) {
    $stmt_clear = $conn->prepare("DELETE FROM chat_data");
    $stmt_clear->execute();
    $stmt_clear->close();
    header("Location: 9customer.php");
}

if (isset($_POST['question'])) {

    $curl = curl_init();
    $str = $_POST['question'];
    $postdata = array(
        "model" => "text-davinci-003",
        "prompt" => $str,
        "temperature" => 0,
        "max_tokens" => 500
    );
    $postdata = json_encode($postdata);

    $retry = 0;
    do {
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.openai.com/v1/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postdata,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer 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',
            ),
        ));

        $response = curl_exec($curl);
        $result = json_decode($response, true);

        if ($retry > 0 && isset($result['error']) && $result['error']['code'] == 'ratelimit-exceeded') {
            sleep(20); // Sleep for 20 seconds before retrying
        }

        $retry++;
    } while ($retry <= 3 && (isset($result['error']) && $result['error']['code'] == 'ratelimit-exceeded'));

    curl_close($curl);

    $newdate = date('Y-m-d');
    if (is_array($result) && array_key_exists("error", $result)) {
        $error_message = "Oops! We ran into an issue: " . $result['error']['message'];
        echo $error_message;
        $message = $error_message;
    } else {
        $message = $result['choices'][0]['text'];
    }
    $botreply = array("answer" => $message, "received_date" => $newdate);

    $stmt = $conn->prepare("INSERT INTO chat_data (message_send, send_date, message_received, received_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $message_send, $send_date, $message_received, $received_date);

    $message_send = $_POST['question'];
    $send_date = date('Y-m-d');
    $message_received = $botreply['answer'];
    $received_date = $botreply['received_date'];
    $stmt->execute();
    $stmt->close();

    $fetch_stmt = $conn->prepare("SELECT message_send, send_date, message_received, received_date FROM chat_data ORDER BY id ASC");
    $fetch_stmt->execute();
    $fetch_result = $fetch_stmt->get_result();

    while ($row = $fetch_result->fetch_assoc()) {
        $conversation[] = array(
            "user" => $row['message_send'],
            "user_date" => $row['send_date'],
            "bot" => $row['message_received'],
            "bot_date" => $row['received_date']
        );
    }

    $fetch_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: auto;
            overflow: hidden;
        }

        .user-message,
        .bot-message {
            display: flex;
            align-items: center;
            margin: 5px 0;
        }

        .user-avatar,
        .bot-avatar {
            width: 40px; /* Fixed width */
            height: 40px; /* Fixed height */
            overflow: hidden; /* Hide overflow content */
            border-radius: 50%; /* Make it a circle */
            margin-left: 10px;
            margin-right: 10px;
            text-align: center;
            line-height: 40px; /* Center text vertically */
            font-size: 18px; /* Adjust the font size as needed */
            color: white;
        }

        .user-avatar {
            background-color: #4CAF50;
        }

        .bot-avatar {
            background-color: #008CBA;
        }

        .user-message-text,
        .bot-message-text {
            text-align: left;
            background-color: #fff;
            color: #333;
            border-radius: 10px;
            padding: 10px;
            margin-left: 10px;
            overflow-wrap: break-word; /* Ensure long words break to the next line */
        }

        .user-message {
            justify-content: flex-end;
        }

        .bot-message {
            justify-content: flex-start;
        }

        form {
            margin-top: 20px;
        }
    </style>
    <title>Chatbot Form</title>
</head>
<body>
    <form method="post" action="">
        <label for="question">Ask a question:</label>
        <input type="text" name="question" required>
        <button type="submit">Submit</button>
    </form>

    <!-- Form for "Go to Dashboard" button -->
    <form method="post" action="">
        <button type="submit" name="go_to_dashboard">Go To Dashboard</button>
    </form>

    <?php
    foreach ($conversation as $entry) {
        if ($entry['user'] != "") {
            $userInitial = strtoupper(substr($entry['user'], 0, 1));
            echo "<div class='user-message'>
                      <div class='user-message-text'>{$entry['user']} ({$entry['user_date']}) :User</div>
                      <div class='user-avatar'>$userInitial</div>
                  </div>";
        }
        if ($entry['bot'] != "") {
            $botInitial = strtoupper(substr("Bot", 0, 1));
            echo "<div class='bot-message'>
                      <div class='bot-avatar'>$botInitial</div>
                      <div class='bot-message-text'>Bot: {$entry['bot']} ({$entry['bot_date']})</div>
                  </div>";
        }
    }
    ?>
</body>
</html>

