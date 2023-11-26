User
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <style>
        /* Your existing styles here */
        .ai-message {
            margin-top: 10px;
            color: #333; /* Adjust the color as needed */
        }
    </style>
</head>
<body>
    <div class="container" id="chat-container">
        <h2>Chat Interface</h2>
        <div id="chat-content"></div>
        <textarea id="user-input" placeholder="Type your message..."></textarea>
        <button onclick="sendMessage()">Send</button>
    </div>
    <button id="open-chat-button" onclick="openChat()">Open Chat</button>

    <script>
        function openChat() {
            document.getElementById('chat-container').style.display = 'block';
        }

        function sendMessage() {
            var userInput = document.getElementById('user-input').value;
            document.getElementById('chat-content').innerHTML += '<p><strong>User:</strong> ' + userInput + '</p>';

            fetch('https://api.openai.com/v1/completions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer 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'
                },
                body: JSON.stringify({
                    prompt: userInput,
                    max_tokens: 50
                })
            })
            .then(response => response.json())
            .then(data => {
                var aiResponse = data.choices[0].text.trim();

                // Create a div element for the AI response
                var aiContent = document.createElement('div');
                aiContent.classList.add('ai-message');

                // Create a strong element for the "AI:" label
                var aiLabel = document.createElement('strong');
                aiLabel.textContent = 'AI:';

                // Create a paragraph element for the AI response text
                var aiText = document.createElement('p');
                aiText.textContent = aiResponse;

                // Append the label and text to the AI content div
                aiContent.appendChild(aiLabel);
                aiContent.appendChild(aiText);

                // Append the AI content div to the chat container
                document.getElementById('chat-content').appendChild(aiContent);
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>