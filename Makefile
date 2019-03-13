#
# A simple makefile to run the container.
#

all:
	docker build --no-cache -t unkown .
	docker run --privileged -p 0.0.0.0:80:80 unknown
