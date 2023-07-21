<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
<div class="container">
<h2>Enter Card Number and Expiration Date (one set per line):</h2>
    <textarea id="cardData" rows="10" cols="50"></textarea><br>
    <div class="form-wrapper">
    <button id="saveButton">Save and Convert Cards</button>
</div>
</div>
    <script>
        document.getElementById("saveButton").addEventListener("click", function() {
            var cardData = document.getElementById("cardData").value;

            if (!cardData.trim()) {
                // Display error message
                alert("Please enter card numbers and expiration dates in the textarea.");
                return;
            }
            var cardsArray = cardData.trim().split("\n");

            for (var i = 0; i < cardsArray.length; i++) {
                cardsArray[i] = cardsArray[i].replace(/\t/g, " ");
            }
            // AJAX call to send the card data to the PHP script
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Download the response as a text file
                        var data = xhr.responseText;
                        var blob = new Blob([data], { type: 'text/plain' });
                        var url = window.URL.createObjectURL(blob);
                        var a = document.createElement('a');
                        a.href = url;
                        a.download = 'converted_cards.txt';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);

                        alert("Cards have been converted to tokens and saved as 'converted_cards.txt'!");
                    } else {
                        alert("Error occurred while converting cards to tokens.");
                    }
                }
            };

            xhr.open("POST", "cardconversion.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("cards=" + encodeURIComponent(JSON.stringify(cardsArray)));
        });
    </script>
</body>
</html>