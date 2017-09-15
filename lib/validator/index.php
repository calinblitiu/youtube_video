<?

session_start();
$ts = time();

?><html>
<head>
<title>Image Validator Test Page</title>
</head>
<body>
<script language="JavaScript"><!--
ts = <?= $ts ?>;
--></script>
<center>
<form method="post">
<h1>Image Validator Test Page</h1>
<p><img id="__code__" src="code.php?id=<?= $ts ?>" style="border:1px solid #000000" /><br><a href="no_matter" onclick="document.getElementById('__code__').src = 'code.php?id=' + ++ts; return false">click for new code</a></p>
<p><input type="text" name="code" /></p>
<p><input type="submit" value="Check!" /></p>
</form><?

if (isset($_POST['code']))
    echo (md5(strtoupper($_POST['code'])) == $_SESSION['__img_code__'])
        ? "Valid!" : "Invalid!";

?><a href="http://gscripts.net/" title="php scripts">Php Scripts</a></center>
</body>
</html>