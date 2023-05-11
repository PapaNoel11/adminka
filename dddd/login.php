<?php
  session_start();
  // Подключение к базе данных
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "users";

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Обработка данных формы авторизации
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = mysqli_real_escape_string($conn, $_POST["login"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    // Поиск пользователя в базе данных
    $sql = "SELECT * FROM users WHERE login='$login'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      if (password_verify($password, $user["password"])) {
        // Авторизация успешна
        $_SESSION["user_id"] = $user["id"];
        header("Location: admin.php");
        exit();
      } else {
        echo "Неправильный логин или пароль";
      }
    } else {
      echo "Неправильный логин или пароль";
    }
  }

  $conn->close();
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  Логин: <input type="text" name="login"><br>
  Пароль: <input type="password" name="password"><br>
  <input type="submit" value="Войти">
</form>

<li><a href="register.php">Регистрация</a></li>