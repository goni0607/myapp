@servers(['web' => 'myapp'])

@task('hello', ['no' => ['web']])
	HOSTNAME='192.168.49.130';
	echo "Hello Envoy! Responding from $HOSTNAME";
@endtask