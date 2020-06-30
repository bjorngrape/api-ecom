<h2>Register</h2>

    <form action="http://localhost:8080/api-ecom/v1/users/register.php" method="POST">
        <input type="text" name="username" placeholder="Username" /><br />
        <input type="password" name="password" placeholder="Password" /><br />
        <input type="submit" value="Sign up" />
    </form>

<h2>Log in</h2>

    <form action="http://localhost:8080/api-ecom/v1/users/login.php" method="POST">
        <input type="text" name="username" placeholder="Username" /><br />
        <input type="password" name="password" placeholder="Password" /><br />
        <input type="hidden" name="user_id">
        <input type="submit" value="Log in" />
    </form>
