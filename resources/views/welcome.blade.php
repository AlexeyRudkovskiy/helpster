<html>
<head>
    <title>Welcome</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-100">
<div id="app"></div>
<script>
    window.widget_path = '{{ Vite::asset('resources/js/widget.js') }}';
</script>
@vite('resources/js/app.js')
</body>
</html>
