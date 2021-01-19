<?php
include "config.php";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) :
    header("location: /");
    exit;
endif;


$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") :
    $usernameVal = $_POST['username'];
    $passwordVal = $_POST['password'];

    if (empty(trim($usernameVal))) :
        $username_err = "Por favor, introduce tu nombre de usuario.";
    else :
        $username = trim($usernameVal);
    endif;

    if (empty(trim($passwordVal))) :
        $password_err = "Por favor, introduce tu contraseña.";
    else :
        $password = trim($passwordVal);
    endif;

    if (empty($username_err) && empty($password_err)) :

        $sql = "SELECT id, username, password, email, joined, superuser FROM users WHERE username = ?";

        if ($stmt = $database->prepare($sql)) :
            $stmt->bind_param("s", $param_username);

            $param_username = $username;

            if ($stmt->execute()) :

                $stmt->store_result();

                if ($stmt->num_rows == 1) :

                    $stmt->bind_result($id, $username, $hashed_password, $email, $joined, $superuser);
                    if ($stmt->fetch()) :
                        if (password_verify($password, $hashed_password)) :
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["email"] = $email;
                            $_SESSION["joined"] = $joined;
                            $_SESSION["superuser"] = $superuser;

                            header("location: /");
                        else :
                            $password_err = "La contraseña introducida no es válida.";
                        endif;
                    endif;
                else :
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                endif;
            else :
                echo "Oops! Something went wrong. Please try again later.";
            endif;

            $stmt->close();
        endif;
    endif;
    $database->close();
endif;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Silent Azure - Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="/css/index.css">
    <style>
        body {
            margin: 0;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="my-container" style="height: 100%">
        <div class="form-wrapper" style="width: 500px; height: fit-content; border: 1px solid black; margin: auto; padding: 20px; background-color: #444444; border-radius: 1em;">
            <div class="content-text">
                <h3 style="margin: 0;">Iniciar Sesión</h3><br>
            </div>
            <form method="POST">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label>
                        <div class="content-text">Nombre de usuario</div>
                    </label>
                    <input type="text" name="username" id="username" class="form-control" style="width: 60%;" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>
                        <div class="content-text">Contraseña</div>
                    </label>
                    <input type="password" name="password" id="password" class="form-control" style="width: 60%;">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" value="Iniciar sesión" class="btn btn-primary" style="width: 130px;">
                    <input type="reset" class="btn btn-default" style="width: 130px;">
                </div>
                <p class="content-text">¿No tienes una cuenta? <a href="register.php">Crea una</a></p>
            </form>
        </div>

    </div>
</body>

</html>