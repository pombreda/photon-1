@if (count($errors->all()) > 0)
<script>
	$.pnotify({
	    title: 'Error',
	    type: 'info',
	    text: 'Please check the form below for errors.',
	    hide: false
	});
</script>
@endif

@if ($message = Session::get('success'))
<script>
	$.pnotify({
	    title: 'Success',
	    type: 'info',
	    text: '{{ $message }}'
	});
</script>
@endif

@if ($message = Session::get('error'))
<script>
	$.pnotify({
	    title: 'Error',
	    type: 'info',
	    text: '{{ $message }}',
	    hide: false
	});
</script>
@endif

@if ($message = Session::get('warning'))
<script>
	$.pnotify({
	    title: 'Warning',
	    type: 'info',
	    text: '{{ $message }}',
	    hide: false
	});
</script>
@endif

@if ($message = Session::get('info'))
<script>
	$.pnotify({
	    title: 'Info',
	    type: 'info',
	    text: '{{ $message }}',
	    hide: false
	});
</script>
@endif
