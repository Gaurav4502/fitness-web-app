document.addEventListener("DOMContentLoaded", function () {
    const userInput = document.getElementById("userInput");
    const chatbox = document.getElementById("chatbox");

    userInput.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            sendMessage();
        }
    });

    document.getElementById("sendButton").addEventListener("click", sendMessage);

    function sendMessage() {
        let userText = userInput.value.trim();
        if (userText === "") return;

        // Append user message
        let userHtml = `<p class="userText"><span>${userText}</span></p>`;
        chatbox.innerHTML += userHtml;

        // Clear input field
        userInput.value = "";

        // Send request to chatbot
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "chatbot.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let botHtml = `<p class="botText"><span>${xhr.responseText}</span></p>`;
                chatbox.innerHTML += botHtml;

                // Auto-scroll to the latest message
                chatbox.scrollTop = chatbox.scrollHeight;
            }
        };

        xhr.send("message=" + encodeURIComponent(userText));
    }
});
