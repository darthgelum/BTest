<h1>Dashboard</h1>
<br>
<h2>Name:</h2>
<p><?= $var("name"); ?> <?= $var("surname"); ?></p>
<h2>Balance:</h2>
<p><?= $var("balance"); ?></p>
<form action="/drop_money" method="post">
    <input type="number" name="sum" value="100">
    <button type="submit">Send money</button>
</form>