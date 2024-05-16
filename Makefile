init:
	docker build -t tmpfile-manager:8.2 ./

exec:
	docker run --name tmpfile-manager --rm --interactive --tty --volume ${PWD}:/usr/local/packages/tmpfile-manager/ tmpfile-manager:8.2 /bin/bash
