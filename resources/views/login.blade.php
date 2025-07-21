<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200 flex justify-center items-center h-screen">
    <form method="POST" action="{{ route('login.submit') }}" class="bg-white p-6 rounded shadow w-80">
        @csrf
        <h2 class="text-xl font-bold mb-4">Welcome Back</h2>
        <input type="text" name="name" placeholder="Nama" class="w-full p-2 border rounded mb-2">
        <input type="password" name="password" placeholder="Password" class="w-full p-2 border rounded mb-2">
        <a href="#" class="text-sm text-blue-500">Reset Password?</a>
        <button type="submit" class="w-full bg-blue-500 text-white py-2 mt-4 rounded">Login</button>
    </form>
</body>
</html>
