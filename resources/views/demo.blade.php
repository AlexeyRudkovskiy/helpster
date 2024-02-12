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
        initHelpster('c13b17cb-c351-31b5-bc52-cf7825be1f6e', params);
    })
</script>
</body>
</html>
