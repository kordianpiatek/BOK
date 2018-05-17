<div class="row" id="content">
    <div class="col-md-12">
        <div class="page-header">
            <h2>Załóż konto</h2>
        </div>
        <form class="form-inline" action="" method="post">
            <div class="form-group">
                <label for="login">Login:</label>
                <input type="text" class="form-control" id="login" name="login">
            </div>
            <div class="form-group">
                <label for="pwd">Hasło:</label>
                <input type="password" class="form-control" id="pwd" name="password">
            </div>
            <div class="form-group">
                <label for="role">Rola:</label>
                <label><input type="radio" name="role" value="client" checked="checked">Klient</label>
                <label><input type="radio" name="role" value="support">Support</label>
            </div>
            <button type="submit" class="btn btn-default">Zarejestruj</button>
        </form>
        <form action="../controllers/LoginController.php" method="post">
            <button type="submit" class="btn btn-primary">Zaloguj się</button>
        </form>
    </div>
</div>