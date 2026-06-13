<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="secondary.css">
    <title>E-Kalenarz</title>
</head>
<body>
    <header id="header">
            <h1>E-Kalendarz</h1>
    </header>
    <nav id="nav">
        <ul class="links">
            <li>
                <a href="index.html">Powrót</a>
            </li>
            <li>
                <a href="register.html">Rejestracja</a>
            </li>
        </ul>
    </nav>

    <div id="main">
        <div id="post">
            <h2>Zaloguj się</h2>
            <form id="form" action="login.php" method="post">
                <div class="row">
                    <input type="text" id="login" name="login" placeholder="Nazwa Użytkownika" class="col-6">
                    <div class="col-6"></div>
                    <input type="password" id="password" name="password" placeholder="Hasło" class="col-6">
                    <div class="col-12"></div>    
                    <input type="hidden" id="hash" value="nothing" name="hash">
                    <input type="hidden" id="ready"; name="ready">
                    <button id="send" name="send" class="primary col-2">Dodaj</button>
                    <div class="col-12"></div>    
                    <button id="reset" class="col-2">Wczyść</button>
                </div>
            </form>
            <?php
            if(isset($_POST['send'])){
                $_POST['hash'] = hash('sha256', $_POST['password']);
                $_POST['ready'] = $_POST['hash'];
            }
            ?>
            <div>
                <span id="alert"></span>  
                <span id="message"></span>
                <hr>
            </div>
            <script type="text/javascript" src="sha256.js"></script>
            <script type="text/javascript">
                const root = document.location.origin+"/rest2zip";
                const API_USR = root+"/api/users/index.php";

                const sender = document.getElementById("send");
                const login = document.getElementById("login");
                const password = document.getElementById("password");
                const hashed = document.getElementById("hash");
                const qwe = document.getElementById("ready");
                let hash = "";
                const message = document.getElementById("message");
                const alarm = document.getElementById("alert");



                sender.addEventListener('click', (e) =>{
                    e.preventDefault();
                    if(!login.value || !password.value){
                        alarm.innerHTML = "Wypełnij wszystkie pola";
                        message.innerHTML = "";
                    }
                    else{
                        logIn(login.value, hex_sha256(password.value));
                    }
                    
                })

               

                async function logIn(login, pass){
                    console.log("IN logIn()");
                    if(!login || !pass){
                        alarm.innerHTML = "Wypełnij wszystkie pola";
                        message.innerHTML = "";
                    }
                    else{
                        console.log(login);
                        const found = await findUser(login);
                        if(found){
                            if(pass === found[0].password){
                                alarm.innerHTML = "";
                                message.innerHTML = "Zalogowano";
                                alert("Zalogowano jako "+login);
                                document.cookie = `login=${login}`;
                                document.cookie = `logged=true`;
                            }
                            else{
                                alarm.innerHTML = "Złe hasło";
                                message.innerHTML = "";                                
                            }
                        }
                        else{
                            alarm.innerHTML = "Nie ma takiego użytkownika";
                            message.innerHTML = "";
                        }
                    }   
                }

                async function findUser(login){
                    console.log("IN findUser()");
                    let found = false;
                    try{
                        const response = await fetch(`${API_USR}?login=${login}`,{
                            method : 'GET'
                        });
                        const result = await response.json();
                        if(await result.length > 0){
                            return result;
                        }
                        else{
                            found = false;
                        }
                        return found;
                    }
                    catch(error){
                        console.log("błąd przy sprawdzaniu bd", error);
                    }
                }

                
            </script>
        </div>
    </div>
</body>
</html>   