{{-- resources/views/contact.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Contact Us</h1>
        <form id='msgForm' class="space-y-4">
            <div>
                <label for="name" class="block text-gray-700 font-semibold mb-1">Name</label>
                <input type="text" name="name" id="name" required
                    class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="email" class="block text-gray-700 font-semibold mb-1">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="message" class="block text-gray-700 font-semibold mb-1">Message</label>
                <textarea name="message" id="message" rows="4" required
                    class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white font-semibold p-2 rounded hover:bg-blue-700 transition">
                Send Message
            </button>
        </form>
        <p id='msg_reply'></p>
    </div>
    <script>
        var MsgReply = document.getElementById('msg_reply');
        let msgForm = document.getElementById('msgForm').onsubmit = function(e) {
            e.preventDefault();
            let name = document.getElementById('name').value;
            let email = document.getElementById('email').value;
            let message = document.getElementById('message').value;

            fetch('/api/contact/submit', {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    "name": name,
                    "email": email,
                    "message": message
                })
            }).then(res => res.json()).then((res) => {

                MsgReply.innerText = res;
                console.log(res);
            })
        }
    </script>
</body>

</html>
