<?php echo $doc_type; ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="<?php echo $content_type; ?>;charset=<?php echo $charset; ?>" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<title>Mobile Yammer</title>
<?php echo $this->Html->css('/themed/mobile/css/screen.css'); ?>
<?php echo $scripts_for_layout; ?>
</head>
<body>
<div id="container">

<div id="header">
Mobile Yammer
</div>

<div id="content">
<?php echo $content_for_layout; ?>
</div>

<div id="footer">
</div>

</div>
</body>
</html>
