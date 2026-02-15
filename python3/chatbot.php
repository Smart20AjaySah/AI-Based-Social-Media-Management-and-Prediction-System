<!DOCTYPE html>
<html>
<head>
    <title>Smart AI Chat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset & body */
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        /* Chat container */
        #chat-box {
            width: 90%;
            max-width: 600px;
            margin: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            height: 90vh;
        }

        /* Header with Back button */
        #chat-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        #back-btn {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            margin-right: 10px;
            font-size: 1rem;
            transition: color 0.2s;
        }

        #back-btn:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        #chat-header h2 {
            margin: 0;
            flex: 1;
            text-align: center;
            color: #333;
        }

        /* Messages container */
        #messages {
            flex: 1;
            overflow-y: auto;
            padding-right: 5px;
            margin-bottom: 15px;
        }

        /* Individual message */
        .message {
            margin: 8px 0;
            padding: 10px 14px;
            border-radius: 12px;
            max-width: 80%;
            word-wrap: break-word;
            animation: fadeIn 0.3s ease-in;
        }

        /* User message */
        .user {
            text-align: right;
            background: #d1e7ff;
            color: #004085;
            margin-left: auto;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        /* AI message */
        .ai {
            text-align: left;
            background: #f1f1f1;
            color: #333;
            margin-right: auto;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        /* Input area */
        #input-area {
            display: flex;
            gap: 10px;
        }

        #msg {
            flex: 1;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
            outline: none;
            transition: all 0.2s ease;
        }

        #msg:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.3);
        }

        #send {
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        #send:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        #send:active {
            transform: translateY(1px);
        }

        /* Scrollbar styling */
        #messages::-webkit-scrollbar {
            width: 6px;
        }

        #messages::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.2);
            border-radius: 3px;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(5px);}
            to {opacity: 1; transform: translateY(0);}
        }

        /* Responsive for small phones */
        @media (max-width: 480px) {
            #chat-box {
                width: 95%;
                padding: 15px;
            }

            #msg {
                font-size: 0.95rem;
                padding: 10px;
            }

            #send {
                padding: 10px 15px;
                font-size: 0.95rem;
            }

            .message {
                font-size: 0.95rem;
                padding: 8px 10px;
            }

            #back-btn {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<div id="chat-box">
    <div id="chat-header">
        <a id="back-btn" href="/ajay/owner/index.php" onclick="clearHistory(event)">&larr; Back</a>
        <h2>Smart AI Chat</h2>
    </div>

    <div id="messages"></div>
    <div id="input-area">
        <input type="text" id="msg" placeholder="Type your message...">
        <button id="send">Send</button>
    </div>
</div>

<script>
document.getElementById("send").onclick = sendMessage;
document.getElementById("msg").addEventListener("keypress", function(e){
    if(e.key === "Enter") sendMessage();
});

function addMessage(text, type){
    let container = document.getElementById("messages");
    container.innerHTML += `<div class="message ${type}">${text}</div>`;
    container.scrollTop = container.scrollHeight; // auto scroll
}

function sendMessage(){
    let text = document.getElementById("msg").value.trim();
    if(text === "") return;

    addMessage(text, "user");
    document.getElementById("msg").value = "";

    fetch("/ajay/python3/ask.php", {
        method: "POST",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify({message:text})
    })
    .then(res => res.json())
    .then(data => {
        addMessage(data.reply, "ai");
    })
    .catch(err => {
        console.error(err);
        addMessage("⚠ Backend not reachable!", "ai");
    });
}

// Clear chat and backend memory on Back
function clearHistory(event){
    event.preventDefault();

    // Clear frontend messages
    document.getElementById("messages").innerHTML = "";

    // Call PHP to clear memory.json
    fetch("/ajay/python3/clear_memory.php")
    .finally(() => {
        // Redirect to link
        window.location.href = event.target.href;
    });
}
</script>

</body>
</html>
