<html>
<head>
    <title>Demo</title>
</head>
<body>
<div>hello world</div>
<script src="https://helpster.pics/widget/bundle.js"></script>
<script>
    window.addEventListener('load', function () {
        const params = {
            @if(auth()->check())
            identifier: '{{ md5(auth()->id()) }}',
            name: '{{ auth()->user()->name }}'
            @endif
        };
        initHelpster('850560ff-4f63-3b92-8ce3-c97833d25794', params);
    })
</script>
</body>
</html>
