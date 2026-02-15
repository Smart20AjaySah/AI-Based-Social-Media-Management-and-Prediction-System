<?php 
include 'home-header.php'; 

$loggedInUser = isset($_SESSION['username']) ? $_SESSION['username'] : ''; 
?>

<main>
    <div class="chat-with-user-container">
        <h2>Chat with <span id="chatUser"></span></h2>
        <div class="messages" id="messages"></div>
        <input type="text" id="messageInput" placeholder="Type a message...">
        <button id="sendBtn">Send</button>
        
        <div style="display: flex; justify-content: center; margin-top: 10px;">
            <button id="clearChatBtn" class="clear-btn" style="background-color: red; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Clear Chat</button>
        </div>
    </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const chatUser = urlParams.get('username');

    if (chatUser) {
        document.getElementById("chatUser").innerText = chatUser;
        fetchMessages();
        setInterval(fetchMessages, 2000);
    }

    document.getElementById("sendBtn").addEventListener("click", sendMessage);
    document.getElementById("clearChatBtn").addEventListener("click", clearChat);
});

function fetchMessages() {
    let receiver = document.getElementById("chatUser").innerText;

    fetch("fetch-messages.php?receiver=" + encodeURIComponent(receiver))
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error("Fetch Error:", data.error);
            return;
        }

        let messagesDiv = document.getElementById('messages');
        messagesDiv.innerHTML = "";

        let loggedInUser = "<?php echo $_SESSION['username']; ?>"; // Ensure session username is set
        let hasUnseenMessages = false; // Track if there are any unseen messages

        data.messages.forEach(msg => {
            let messageElement = document.createElement('div');

            if (msg.sender === loggedInUser) {
                messageElement.classList.add("sent");
                messageElement.innerHTML = `${msg.message} 
                ${msg.seen == 1 ? '<span class="seen-status">✓ Seen</span>' : ''}`;
            } else {
                messageElement.classList.add("received");
                messageElement.innerHTML = `${msg.message}`;

                if (msg.seen == 0) {
                    hasUnseenMessages = true; // Message is unseen
                }
            }

            messagesDiv.appendChild(messageElement);
        });

        messagesDiv.scrollTop = messagesDiv.scrollHeight;

        // Mark messages as seen if there are any unseen messages
        if (hasUnseenMessages) {
            updateSeenStatus(receiver);
        }
    })
    .catch(error => console.error("Error fetching messages:", error));
}

function sendMessage() {
    let messageInput = document.getElementById('messageInput');
    let message = messageInput.value.trim();
    let receiver = document.getElementById("chatUser").innerText;

    if (!receiver) {
        console.error("Receiver not found");
        return;
    }

    if (message !== "") {
        fetch("send-message.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "receiver=" + encodeURIComponent(receiver) + "&message=" + encodeURIComponent(message)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = "";
                fetchMessages();
                updateSeenStatus(receiver); // ✅ New Message पर भी Seen Update होगा
            } else {
                console.error("Error sending message:", data.error);
            }
        })
        .catch(error => console.error("Fetch Error:", error));
    } else {
        console.warn("Empty message cannot be sent");
    }
}

function clearChat() {
    let receiver = document.getElementById("chatUser").innerText;

    if (confirm("Are you sure you want to clear this chat?")) {
        fetch("clear-chat.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "receiver=" + encodeURIComponent(receiver)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchMessages();
            } else {
                console.error("Error clearing chat:", data.error);
            }
        })
        .catch(error => console.error("Fetch Error:", error));
    }
}

// Mark messages as seen when opened
function updateSeenStatus(sender) {
    fetch("seen.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "sender=" + encodeURIComponent(sender) + "&receiver=" + encodeURIComponent("<?php echo $_SESSION['username']; ?>")
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error("Error updating seen status:", data.error);
        }
    })
    .catch(error => console.error("Fetch Error:", error));
}
</script>

<?php include 'home-footer.php'; ?>
