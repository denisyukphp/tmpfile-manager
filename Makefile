up:
	docker build -t tmpfile-manager:php8.5-cli-trixie ./

shell:
	docker run \
		--name tmpfile-manager \
		--rm \
		--interactive \
		--tty \
		--volume ${PWD}:/usr/local/packages/tmpfile-manager/ \
		tmpfile-manager:php8.5-cli-trixie /bin/bash ;
