<?php
include ("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm-password"];

    if (!isset($email, $password, $confirmPassword)) {
        echo "<h2 class='font-bold text-red-950'>Missing fields!</h2>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("<h2 class='font-bold text-red-950'>Invalid email!</h2>");
    }

    if ($password !== $confirmPassword) {
        echo "<h2 class='font-bold text-red-950'>Password mismatch!</h2>";
        exit;
    }

    $isEmailExists = $conn->execute_query("SELECT * FROM users WHERE email=?", [$email]);

    if ($isEmailExists->num_rows > 0) {
        echo "<h2 class='font-bold text-red-950'>Exisitng email found!</h2>";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $createUser = $conn->execute_query("INSERT INTO users (email, password) VALUES (?, ?)", [$email, $hashedPassword]);

    if ($createUser === FALSE) {
        die("Error:" . $conn->error);
    }

    echo "<h2 class='font-bold text-white'>Signing up Successful!</h2>";
    exit;
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div id="show-resp" class='bg-blue-500 text-center py-4'>
            <h2 class="font-bold text-white">Welcome to sign up</h2>
        </div>
        <div class="flex flex-col items-center gap-20 justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div
                class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1
                        class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Sign up
                    </h1>
                    <form class="space-y-4 md:space-y-6" method="post" id="sign-up-form">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                                email</label>
                            <input type="email" name="email" id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="name@company.com" required>
                        </div>
                        <div>
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                        </div>
                        <div>
                            <label for="confirm-password"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm
                                password</label>
                            <input type="password" name="confirm-password" id="confirm-password" placeholder="••••••••"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Create
                            an account</button>
                        <label class="inline-flex items-center me-5 cursor-pointer">
                            <input type="checkbox" id="ajaxToggler" class="sr-only peer" checked>
                            <div
                                class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-4 peer-focus:ring-orange-300 dark:peer-focus:ring-orange-800 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-orange-500">
                            </div>
                            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">AJAX</span>
                        </label>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                            Already have an account? <a href="sign_in.php"
                                class="font-medium text-primary-600 hover:underline dark:text-primary-500">Sign in
                                here</a>
                        </p>
                    </form>
                </div>
            </div>


        </div>
    </section>

    <script>

        const signUpForm = document.getElementById("sign-up-form");
        const showResp = document.getElementById("show-resp");

        const ajaxToggler = document.getElementById("ajaxToggler");

        signUpForm.addEventListener("submit", function (e) {
            if (!ajaxToggler.checked) return;

            e.preventDefault();
            processRequest();
        })


        const processRequest = function () {
            const formData = new FormData(signUpForm);
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>", true);

            xhr.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    showResp.innerHTML = this.responseText;
                }
            }

            xhr.send(formData);
            // signUpForm.reset();
        }

    </script>
</body>

</html>